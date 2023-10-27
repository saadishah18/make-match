import React from 'react';
import { Link } from '@inertiajs/inertia-react';

export default function ResponsiveNavLink({ method = 'method', href, active = false, children }) {
    return (
        <Link
            method={method}
            as="button"
            href={href}
            // active={active}
            className={
                active  ? 'flex w-full items-center  justify-start px-5 pt-2 pb-2 border-l-4 gap-4 text-base border-themecolor bg-themebgcolor font-medium leading-5 text-black focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out min-h-[60px] border-b-2 border-b-themebgcolor'
                    : 'flex w-full items-center px-5 pt-2 pb-2 border-l-4 gap-4 border-transparent text-base font-medium leading-5 text-black hover:text-themecolor hover:border-themecolor hover:border-b-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out min-h-[60px] border-b-2 border-b-themebgcolor'
            }
        >
            {children}
        </Link>
    );
}
