import React from 'react';
import { createRoot } from 'react-dom/client';
import ExampleComponent from './components/ExampleComponent';

// Mount by id (single mount)
const el = document.getElementById('react-app');
if (el) {
  // read props from data-props attribute if present
  const props = el.dataset.props ? JSON.parse(el.dataset.props) : {};
  createRoot(el).render(<ExampleComponent {...props} />);
}

// Optional: mount multiple components by `data-react-component` attribute
document.querySelectorAll('[data-react-component]').forEach((node) => {
  const name = node.dataset.reactComponent; // you can mount different components by name
  const props = node.dataset.props ? JSON.parse(node.dataset.props) : {};
  // For demo we only have ExampleComponent; expand as needed
  createRoot(node).render(<ExampleComponent {...props} />);
});