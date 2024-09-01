import { catchErrors } from './middlewares/catch-errors';

const { apiFetch: _apiFetch } = window.wp;

_apiFetch.use( catchErrors );

export const apiFetch = _apiFetch;
