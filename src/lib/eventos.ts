import { getCollection, type CollectionEntry } from 'astro:content';
import { hoyLocal, sumarDias, sumarMeses } from './fechas';

export type Evento = CollectionEntry<'eventos'>;

/** Una fecha concreta de un evento (los recurrentes generan varias). */
export interface Ocurrencia {
  slug: string;
  fecha: string;
  fechaFin?: string;
  evento: Evento['data'];
}

// Cuánto hacia adelante generamos fechas de eventos recurrentes.
const HORIZONTE_DIAS = 180;

export function fechasDe(evento: Evento, desde: string): string[] {
  const { fecha, repite } = evento.data;
  if (!repite) return [fecha];

  const limite = [repite.hasta, sumarDias(desde, HORIZONTE_DIAS)].filter(Boolean).sort()[0]!;
  const fechas: string[] = [];
  for (let i = 0; fechas.length < 200; i++) {
    const f =
      repite.frecuencia === 'mensual'
        ? sumarMeses(fecha, i)
        : sumarDias(fecha, i * (repite.frecuencia === 'semanal' ? 7 : 14));
    if (f === null) continue;
    if (f > limite) break;
    if (f >= desde) fechas.push(f);
  }
  return fechas;
}

/** Ocurrencias desde ayer en adelante, ordenadas. El navegador oculta las que ya pasaron. */
export async function obtenerOcurrencias(): Promise<Ocurrencia[]> {
  const desde = sumarDias(hoyLocal(), -1);
  const eventos = await getCollection('eventos');
  const ocurrencias: Ocurrencia[] = [];

  for (const ev of eventos) {
    const duracion = ev.data.fechaFin ? diasEntre(ev.data.fecha, ev.data.fechaFin) : 0;
    for (const f of fechasDe(ev, sumarDias(desde, -duracion))) {
      const fin = duracion ? sumarDias(f, duracion) : undefined;
      if ((fin ?? f) < desde) continue;
      ocurrencias.push({ slug: ev.id, fecha: f, fechaFin: fin, evento: ev.data });
    }
  }

  return ocurrencias.sort(
    (a, b) => a.fecha.localeCompare(b.fecha) || (a.evento.hora ?? '').localeCompare(b.evento.hora ?? ''),
  );
}

export function diasEntre(a: string, b: string) {
  return Math.round((Date.parse(b) - Date.parse(a)) / 86_400_000);
}

export const lugarCorto = (l: Evento['data']['lugar']) => [l.nombre, l.ciudad].join(', ');

export const urlMapa = (l: Evento['data']['lugar']) =>
  l.mapa ??
  `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(
    [l.nombre, l.direccion, l.ciudad, l.provincia].filter(Boolean).join(', '),
  )}`;

export const urlInstagram = (u: string) =>
  u.startsWith('http') ? u : `https://instagram.com/${u.replace(/^@/, '')}`;

export const urlWhatsapp = (n: string, texto?: string) =>
  `https://wa.me/${n.replace(/\D/g, '')}${texto ? `?text=${encodeURIComponent(texto)}` : ''}`;
