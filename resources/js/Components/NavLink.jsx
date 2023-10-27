import React from "react";
import { Link } from "@inertiajs/inertia-react";

export default function NavLink({ href, active=false, children }) {
    return (
        <Link
            href={href}
            className={
                active
                    ? "flex w-full items-center whitespace-nowrap justify-start px-5 pt-2 pb-2 border-l-4 gap-4 text-base border-themecolor bg-white font-medium leading-5 text-themecolor focus:outline-none transition duration-150 ease-in-out min-h-[60px] border-b-2 border-b-themebgcolor"
                    : "flex w-full items-center whitespace-nowrap px-5 pt-2 pb-2 border-l-4 gap-4 border-transparent text-base font-medium leading-5 text-black hover:text-themecolor hover:border-themecolor hover:border-b-gray-300 focus:outline-none focus:text-themecolor focus:border-themecolor focus:border-b-gray-300 transition duration-150 ease-in-out min-h-[60px] border-b-2 border-b-themebgcolor"
            }
            // as="button"
        >
            {children}
        </Link>
    );
}
