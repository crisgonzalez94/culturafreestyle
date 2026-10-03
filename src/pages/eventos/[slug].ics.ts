import type { APIRoute } from 'astro';
import { getCollection } from 'astro:content';
import { SITIO } from '../../data/sitio';
import { sumarDias } from '../../lib/fechas';
import type { Evento } from '../../lib/eventos';

export async function getStaticPaths() {
  const eventos = await getCollection('eventos');
  return eventos.map((evento) => ({ params: { slug: evento.id }, props: { evento } }));
}

const escapar = (t: string) => t.replace(/\\/g, '\\\\').replace(/\n/g, '\\n').replace(/([,;])/g, '\\$1');
const compacta = (f: string) => f.replaceAll('-', '');
const conHora = (f: string, h: string) => {
  const [hh, mm] = h.split(':');
  return `${compacta(f)}T${hh.padStart(2, '0')}${mm}00`;
};

/** Archivo de calendario (.ics) para "Agendar": funciona con Google Calendar, Apple y Outlook. */
export const GET: APIRoute = ({ props, site, params }) => {
  const { data: ev } = props.evento as Evento;
  const tz = SITIO.zonaHoraria;
  const fin = ev.fechaFin ?? ev.fecha;

  let inicio: string;
  let termino: string;
  if (ev.hora) {
    inicio = `DTSTART;TZID=${tz}:${conHora(ev.fecha, ev.hora)}`;
    // Sin hora de fin, asumimos 2 horas.
    const [h, m] = ev.hora.split(':').map(Number);
    const horaFin = ev.horaFin ?? `${Math.min(h + 2, 23)}:${String(m).padStart(2, '0')}`;
    termino = `DTEND;TZID=${tz}:${conHora(fin, horaFin)}`;
  } else {
    inicio = `DTSTART;VALUE=DATE:${compacta(ev.fecha)}`;
    termino = `DTEND;VALUE=DATE:${compacta(sumarDias(fin, 1))}`;
  }

  const regla = ev.repite
    ? `RRULE:FREQ=${ev.repite.frecuencia === 'mensual' ? 'MONTHLY' : 'WEEKLY'}${
        ev.repite.frecuencia === 'quincenal' ? ';INTERVAL=2' : ''
      }${ev.repite.hasta ? `;UNTIL=${compacta(ev.repite.hasta)}T235959Z` : ''}`
    : null;

  // El link al evento solo se agrega si `site` está configurado en astro.config.mjs.
  const url = site ? new URL(`/eventos/${params.slug}`, site).href : null;
  const lugar = [ev.lugar.nombre, ev.lugar.direccion, ev.lugar.ciudad].filter(Boolean).join(', ');

  const lineas = [
    'BEGIN:VCALENDAR',
    'VERSION:2.0',
    'PRODID:-//Cultura Freestyle//ES',
    'CALSCALE:GREGORIAN',
    'BEGIN:VEVENT',
    `UID:${params.slug}@culturafreestyle`,
    `DTSTAMP:${new Date().toISOString().replace(/[-:]/g, '').slice(0, 15)}Z`,
    inicio,
    termino,
    regla,
    `SUMMARY:${escapar(ev.titulo)}`,
    `LOCATION:${escapar(lugar)}`,
    url && `DESCRIPTION:${escapar(`Más info: ${url}`)}`,
    url && `URL:${url}`,
    'END:VEVENT',
    'END:VCALENDAR',
  ].filter(Boolean);

  return new Response(lineas.join('\r\n'), {
    headers: { 'Content-Type': 'text/calendar; charset=utf-8' },
  });
};
