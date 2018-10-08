export interface IDocGroup {
  id: string;
  description: string;
  fields: Array<ISubDoc>;
}

export interface ISubDoc {
  id: string;
  name: string;
  is_required: number;
}
