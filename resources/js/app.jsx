import './bootstrap';
import '../css/app.css';
import '/public/assets/css/responsive.css';

import React from 'react';
import { createRoot, render } from 'react-dom/client';
import { createInertiaApp } from '@inertiajs/inertia-react';
import { InertiaProgress } from '@inertiajs/progress';
import {resolvePageComponent} from "laravel-vite-plugin/inertia-helpers";
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.jsx`, import.meta.glob('./Pages/**/*.jsx')),
    setup({ el, App, props }) {
        const app = (
            <>
                <App {...props} />
                <ToastContainer />
            </>
        );

        // Create a root using createRoot
        const root = createRoot(el);

        // Render the app using root.render
        root.render(app);
    },
});

InertiaProgress.init({ color: '#4B5563' });
