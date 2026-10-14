'use server';

import { redirect } from 'next/navigation';
import { getDb } from '../lib/db';
import { importWorkbook } from '../lib/importer';

export async function upload(formData: FormData) {
  const file = formData.get('file');
  if (!(file instanceof File) || !file.name.toLowerCase().endsWith('.xlsx')) {
    redirect('/?error=Please choose an .xlsx file');
  }
  let msg: string;
  try {
    const counts = await importWorkbook(getDb(), Buffer.from(await file.arrayBuffer()));
    const done = Object.entries(counts).map(([m, n]) => `${m}: ${n} readings`);
    msg = done.length ? `imported ${done.join(', ')}` : '';
  } catch {
    redirect('/?error=Could not read the workbook');
  }
  redirect(
    msg ? `/?ok=${encodeURIComponent(msg)}` : '/?error=No known sheet found in the workbook',
  );
}
