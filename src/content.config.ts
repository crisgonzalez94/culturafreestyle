import { defineCollection } from 'astro:content';
import { glob } from 'astro/loaders';
import { z } from 'astro/zod';
import { DISCIPLINA_IDS, NIVELES } from './data/disciplinas';

// YAML convierte `2026-10-10` en Date; lo normalizamos a texto 'YYYY-MM-DD'.
const fecha = z.preprocess(
  (v) => (v instanceof Date ? v.toISOString().slice(0, 10) : v),
  z.string().regex(/^\d{4}-\d{2}-\d{2}$/, 'Usá el formato AAAA-MM-DD'),
);
const hora = z.string().regex(/^\d{1,2}:\d{2}$/, 'Usá el formato HH:MM');
const nivel = z.enum(Object.keys(NIVELES) as [keyof typeof NIVELES, ...(keyof typeof NIVELES)[]]);

const eventos = defineCollection({
  loader: glob({ pattern: '**/*.md', base: './src/content/eventos' }),
  schema: z.object({
    titulo: z.string(),
    disciplinas: z.array(z.enum(DISCIPLINA_IDS)).min(1),
    tipo: z.string(),
    etiquetas: z.array(z.string()).default([]),

    fecha,
    fechaFin: fecha.optional(),
    hora: hora.optional(),
    horaFin: hora.optional(),
    repite: z
      .object({
        frecuencia: z.enum(['semanal', 'quincenal', 'mensual']),
        hasta: fecha.optional(),
      })
      .optional(),

    lugar: z.object({
      nombre: z.string(),
      direccion: z.string().optional(),
      ciudad: z.string(),
      provincia: z.string().optional(),
      mapa: z.url().optional(),
    }),

    organiza: z
      .object({
        nombre: z.string(),
        instagram: z.string().optional(),
        web: z.url().optional(),
      })
      .optional(),
    contacto: z
      .object({
        instagram: z.string().optional(),
        whatsapp: z.string().optional(),
        web: z.url().optional(),
      })
      .optional(),
    inscripcion: z.url().optional(),

    nivel: z.array(nivel).default(['todos']),
    costo: z.string().default('Gratis'),
    estado: z.enum(['confirmado', 'a-confirmar', 'suspendido']).default('confirmado'),
    destacado: z.boolean().default(false),
    imagen: z.string().optional(),

    // Cualquier dato extra que no entre en los campos de arriba: "Distancia", "Equipo", "Categorías"...
    datos: z.record(z.string(), z.string()).default({}),
  }),
});

export const collections = { eventos };
