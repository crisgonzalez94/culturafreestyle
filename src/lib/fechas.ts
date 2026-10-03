// Fechas como texto 'YYYY-MM-DD' (día local del evento). Toda la aritmética se hace en UTC
// para evitar corrimientos de zona horaria. Se usa tanto en el build como en el navegador.

const DIAS = ['dom', 'lun', 'mar', 'mié', 'jue', 'vie', 'sáb'];
const DIAS_LARGOS = ['domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'];
export const MESES = ['ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'];
export const MESES_LARGOS = [
  'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio',
  'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre',
];

const aUTC = (f: string) => {
  const [y, m, d] = f.split('-').map(Number);
  return new Date(Date.UTC(y, m - 1, d));
};
const deUTC = (d: Date) => d.toISOString().slice(0, 10);

export const hoyLocal = (ahora = new Date()) =>
  `${ahora.getFullYear()}-${String(ahora.getMonth() + 1).padStart(2, '0')}-${String(ahora.getDate()).padStart(2, '0')}`;

export const sumarDias = (f: string, n: number) => {
  const d = aUTC(f);
  d.setUTCDate(d.getUTCDate() + n);
  return deUTC(d);
};

/** Suma meses manteniendo el día; devuelve null si ese día no existe (ej. 31 de febrero). */
export const sumarMeses = (f: string, n: number) => {
  const [y, m, d] = f.split('-').map(Number);
  const r = new Date(Date.UTC(y, m - 1 + n, d));
  return r.getUTCDate() === d ? deUTC(r) : null;
};

export const diaSemana = (f: string) => aUTC(f).getUTCDay();
export const finDeMes = (f: string) => {
  const [y, m] = f.split('-').map(Number);
  return deUTC(new Date(Date.UTC(y, m, 0)));
};

/** Rango del "finde": si hoy es vie/sáb/dom, desde hoy hasta el domingo; si no, el próximo vie-dom. */
export const rangoFinde = (hoy: string): [string, string] => {
  const dow = diaSemana(hoy);
  if (dow === 0) return [hoy, hoy];
  if (dow >= 5) return [hoy, sumarDias(hoy, 7 - dow)];
  return [sumarDias(hoy, 5 - dow), sumarDias(hoy, 7 - dow)];
};

export const partes = (f: string) => {
  const d = aUTC(f);
  return {
    dia: d.getUTCDate(),
    dow: DIAS[d.getUTCDay()],
    dowLargo: DIAS_LARGOS[d.getUTCDay()],
    mes: MESES[d.getUTCMonth()],
    mesLargo: MESES_LARGOS[d.getUTCMonth()],
    anio: d.getUTCFullYear(),
  };
};

/** "sáb 10 oct" */
export const fechaCorta = (f: string) => {
  const p = partes(f);
  return `${p.dow} ${p.dia} ${p.mes}`;
};

/** "sábado 10 de octubre de 2026" */
export const fechaLarga = (f: string) => {
  const p = partes(f);
  return `${p.dowLargo} ${p.dia} de ${p.mesLargo} de ${p.anio}`;
};

export const etiquetaFrecuencia = (frecuencia: string, fecha: string) => {
  const dia = DIAS_LARGOS[diaSemana(fecha)];
  const plural = dia.endsWith('s') ? dia : `${dia}s`;
  if (frecuencia === 'semanal') return `Todos los ${plural}`;
  if (frecuencia === 'quincenal') return `Cada dos ${plural}`;
  return `Todos los meses, el día ${partes(fecha).dia}`;
};
