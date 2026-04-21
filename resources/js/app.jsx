
import React from 'react';
import { createRoot } from 'react-dom/client';
import AdminDriversPage from './admin/DriversPage';

const el = document.getElementById('app');

if (el) {
  createRoot(el).render(<AdminDriversPage />);
}
