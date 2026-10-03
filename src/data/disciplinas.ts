// Categorías grandes. Para sumar una nueva, agregala acá y ya se puede usar en los eventos.
export const DISCIPLINAS = {
  rollers: { nombre: 'Rollers', emoji: '🛼', acento: 'pink' },
  skate: { nombre: 'Skate', emoji: '🛹', acento: 'blue' },
  bmx: { nombre: 'BMX', emoji: '🚲', acento: 'pink' },
  bici: { nombre: 'Bici', emoji: '🚴', acento: 'blue' },
  scooter: { nombre: 'Scooter', emoji: '🛴', acento: 'pink' },
  running: { nombre: 'Running', emoji: '🏃', acento: 'blue' },
  otro: { nombre: 'Otro', emoji: '✨', acento: 'white' },
} as const;

export type DisciplinaId = keyof typeof DISCIPLINAS;
export const DISCIPLINA_IDS = Object.keys(DISCIPLINAS) as [DisciplinaId, ...DisciplinaId[]];

// Tipos sugeridos. El campo `tipo` es texto libre: si no está acá se muestra tal cual.
export const TIPOS: Record<string, string> = {
  rolleada: 'Rolleada',
  salida: 'Salida grupal',
  encuentro: 'Encuentro',
  jam: 'Jam',
  competencia: 'Competencia',
  clase: 'Clase',
  festival: 'Festival',
  exhibicion: 'Exhibición',
};

export const NIVELES = {
  todos: 'Todos los niveles',
  principiante: 'Principiante',
  intermedio: 'Intermedio',
  avanzado: 'Avanzado',
} as const;

export const nombreTipo = (tipo: string) => TIPOS[tipo] ?? tipo.charAt(0).toUpperCase() + tipo.slice(1);
