// resources/js/components/ExampleComponent.jsx
import React from 'react';

export default function ExampleComponent({ message = 'Hello from React!' }) {
  return (
    <div className="container py-3">
      <div className="card shadow-sm">
        <div className="card-body">
          <h5 className="card-title">React + Bootstrap</h5>
          <p className="card-text">{message}</p>
          <button className="btn btn-primary">Bootstrap Button</button>
        </div>
      </div>
    </div>
  );
}
