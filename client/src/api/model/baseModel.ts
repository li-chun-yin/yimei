export interface BasicPageParams {
  page: number;
  limit: number;
}

export interface BasicFetchResult<T> {
  items: T[];
  total: number;
}

export interface SampleFetchResult<T> {
  items: T[];
  cursor: number | null;
  has_more: boolean;
}