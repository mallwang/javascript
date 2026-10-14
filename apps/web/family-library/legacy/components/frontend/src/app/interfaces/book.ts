export interface Book {
  _id?: string;
  title: string;
  author: string;
  content?: string;
  isbn: number;
  year: number;
  language: string;
  coverImage?: string;
  ownerId: string;
}
