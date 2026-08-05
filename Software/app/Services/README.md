# app/Services

Reserved for business-logic classes that sit between Controllers and
Models — e.g. a future `SensorService` that aggregates/validates data
before a Controller hands it to a view or API response.

Currently empty because the dashboard's live data comes directly from
Firebase on the client side (see `public/assets/js/api.js`); there is
no server-side sensor business logic yet. This will start being used
in **Step 7 (Database Integration)** and **Step 8 (REST API)**.
