import React from "react";
import { HiMenu, HiX } from "react-icons/hi";
import { Disclosure } from "@headlessui/react";
import { Link } from "@inertiajs/inertia-react";

const navigation = [
    {
        name: "Home",
        href: "#banner",
    },
    {
        name: "About Us",
        href: "#aboutus",
    },
    {
        name: "Features",
        href: "#features",
    },
    {
        name: "Screenshots",
        href: "#screenshots",
    },
    {
        name: "Contact Us",
        href: "#contactus",
    },
];

const Header = () => {
    return (
        <header className={`z-10 absolute top-0 left-0 w-full`}>
            <Disclosure as="div" className="container">
                {({ open }) => (
                    <>
                        <div className="flex justify-between items-center py-7 w-full relative flex-wrap">
                            <strong className="w-[180px] sm:w-[262px] h-[40px]">
                                <Link href="/" className="w-full block h-full">
                                <svg width="246" height="40" viewBox="0 0 246 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M38.9289 27.9356L36.4528 30.5641L35.0601 32.0422C34.6975 32.4185 34.3106 32.7483 33.9226 33.0337C32.9002 33.7874 31.8711 34.2156 31.2742 34.2156C30.6165 34.2156 29.9577 33.5722 30.0395 33.0078C30.286 32.2033 31.1924 29.3045 31.7683 27.7745L37.6124 12.1556C40.4101 4.58889 38.4348 0.0800137 32.3453 0.0800137C28.5594 0.0800137 25.5141 1.4489 18.0252 6.60113C18.9305 2.57557 18.107 0 14.6506 0C11.5102 0 8.64066 1.5927 4.76524 5.76747C3.86768 6.7352 2.91596 7.84026 1.8935 9.09669L2.85406 9.89142L2.96349 9.98224L5.48263 7.31692L6.99588 5.71557C8.23057 4.42778 9.79467 3.70333 10.5342 3.70333C11.193 3.70333 11.9336 4.34669 11.8507 4.91111C11.6042 5.71557 10.616 8.61444 10.0401 10.1444L0 37.1134L5.94799 35.2147L7.81827 34.6178C10.8149 26.4413 13.8878 18.3361 16.963 10.1661C17.0978 9.80924 17.2305 9.45459 17.3653 9.09777C22.6324 5.47553 24.7724 4.50888 27.4883 4.50888C31.5207 4.50888 32.5907 7.00444 30.6165 12.3178L25.1018 27.2122C22.7971 33.3311 22.2212 38 27.0771 38C29.5653 38 31.8026 37.0766 34.4466 34.7843C36.0881 33.3614 37.8854 31.4129 39.9989 28.8222L38.9289 27.9367V27.9356Z" fill="white"/>
                                    <path d="M79.68 9.6V32H76V16.032L69.184 27.296H68.736L61.92 16V32H58.24V9.6H62.208L68.96 20.8L75.744 9.6H79.68ZM94.7063 16H98.3863L92.2423 32.512C90.7063 36.6507 88.1036 38.6133 84.4343 38.4V35.168C85.5223 35.232 86.3969 34.9973 87.0583 34.464C87.7196 33.952 88.2636 33.1413 88.6903 32.032L88.8503 31.712L82.0663 16H85.8423L90.6103 27.616L94.7063 16ZM114.211 9.6H117.891V32H115.011L104.451 16.8V32H100.771V9.6H103.651L114.211 24.8V9.6ZM125.405 12.96C124.978 13.3867 124.466 13.6 123.869 13.6C123.272 13.6 122.749 13.3867 122.301 12.96C121.874 12.512 121.661 11.9893 121.661 11.392C121.661 10.7947 121.874 10.2827 122.301 9.856C122.728 9.408 123.25 9.184 123.869 9.184C124.488 9.184 125.01 9.408 125.437 9.856C125.864 10.2827 126.077 10.7947 126.077 11.392C126.077 11.9893 125.853 12.512 125.405 12.96ZM122.141 32V16H125.597V32H122.141ZM143.754 32H139.626L133.066 24.608V32H129.61V9.6H133.066V23.072L139.274 16H143.498L136.458 23.84L143.754 32ZM157.469 16H160.925V32H157.469V29.696C156.168 31.5093 154.301 32.416 151.869 32.416C149.672 32.416 147.795 31.6053 146.237 29.984C144.68 28.3413 143.901 26.3467 143.901 24C143.901 21.632 144.68 19.6373 146.237 18.016C147.795 16.3947 149.672 15.584 151.869 15.584C154.301 15.584 156.168 16.48 157.469 18.272V16ZM148.797 27.68C149.757 28.64 150.963 29.12 152.413 29.12C153.864 29.12 155.069 28.64 156.029 27.68C156.989 26.6987 157.469 25.472 157.469 24C157.469 22.528 156.989 21.312 156.029 20.352C155.069 19.3707 153.864 18.88 152.413 18.88C150.963 18.88 149.757 19.3707 148.797 20.352C147.837 21.312 147.357 22.528 147.357 24C147.357 25.472 147.837 26.6987 148.797 27.68ZM173.37 15.584C175.204 15.584 176.687 16.1707 177.818 17.344C178.948 18.5173 179.514 20.128 179.514 22.176V32H176.058V22.528C176.058 21.3333 175.738 20.416 175.098 19.776C174.458 19.136 173.583 18.816 172.474 18.816C171.258 18.816 170.276 19.2 169.53 19.968C168.783 20.7147 168.41 21.8667 168.41 23.424V32H164.954V9.6H168.41V18.048C169.455 16.4053 171.108 15.584 173.37 15.584ZM196.899 9.6H200.579V32H197.699L187.139 16.8V32H183.459V9.6H186.339L196.899 24.8V9.6ZM212.381 32.416C210.034 32.416 208.039 31.6053 206.397 29.984C204.754 28.3627 203.933 26.368 203.933 24C203.933 21.632 204.754 19.6373 206.397 18.016C208.039 16.3947 210.034 15.584 212.381 15.584C214.749 15.584 216.743 16.3947 218.365 18.016C220.007 19.6373 220.829 21.632 220.829 24C220.829 26.368 220.007 28.3627 218.365 29.984C216.743 31.6053 214.749 32.416 212.381 32.416ZM208.829 27.616C209.789 28.576 210.973 29.056 212.381 29.056C213.789 29.056 214.973 28.576 215.933 27.616C216.893 26.656 217.373 25.4507 217.373 24C217.373 22.5493 216.893 21.344 215.933 20.384C214.973 19.424 213.789 18.944 212.381 18.944C210.973 18.944 209.789 19.424 208.829 20.384C207.869 21.344 207.389 22.5493 207.389 24C207.389 25.4507 207.869 26.656 208.829 27.616ZM241.761 16H245.409L240.385 32H236.993L233.665 21.216L230.305 32H226.913L221.889 16H225.536L228.641 27.04L232.001 16H235.297L238.625 27.04L241.761 16Z" fill="white"/>
                                </svg>
                                </Link>
                            </strong>
                            <nav className="flex items-center">
                                <Disclosure.Button className="lg:hidden border-2 border-white ml-1 p-2 text-white hover:bg-none rounded-lg ">
                                    <span className="sr-only">
                                        Open main menu
                                    </span>
                                    {open ? (
                                        <HiX
                                            className="block h-5 w-5"
                                            aria-hidden="true"
                                        />
                                    ) : (
                                        <HiMenu
                                            className="block h-5 w-5"
                                            aria-hidden="true"
                                        />
                                    )}
                                </Disclosure.Button>
                                <ul className="hidden lg:flex md:items-center md:space-x-16 list-none">
                                    <>
                                        {navigation.map((item, i) => (
                                            <>
                                                <li key={i}>
                                                    <Link
                                                        href={ route().current() == "privacyPolicy" || route().current() == 'termsAndConditions' ? route('navParts',item.href) : item.href}
                                                        className={`text-white text-opacity-70 font-gilroy-medium transitionease-in-out delay-150 text-base relative hover:text-opacity-100 hover:after:w-full py-5 after:absolute after:left-0 after:bottom-0 after:bg-white after:bg-opacity-30 after:h-1 after:w-0 after:transitionease-in-out after:delay-150
                                                            ${
                                                                item.href
                                                                    ? "text-opacity-100"
                                                                    : "text-opacity-70"
                                                            }
                                                        `}
                                                    >
                                                        {item.name}
                                                    </Link>
                                                </li>
                                            </>
                                        ))}
                                    </>
                                </ul>
                                <Disclosure.Panel className="lg:hidden w-full absolute left-0 top-[145px] sm:top-[151px] md:top-[70px] z-20 bg-black2 shadow-lg">
                                    <ul className=" pb-3 space-y-1 bg-white">
                                        {navigation.map((item, i) => (
                                            <>
                                                <li
                                                    className="border-b border-themecolor last:border-0"
                                                    key={i}
                                                >
                                                    <Disclosure.Button
                                                        as="a"
                                                        href={ route().current() == "privacyPolicy" || route().current() == 'termsAndConditions' ? route('navParts',item.href) : item.href}
                                                        className="!text-themecolor text-xl block px-5 py-3 "
                                                    >
                                                        {item.name}
                                                    </Disclosure.Button>
                                                </li>
                                            </>
                                        ))}
                                    </ul>
                                </Disclosure.Panel>
                            </nav>
                        </div>
                    </>
                )}
            </Disclosure>
        </header>
    );
};

export default Header;
