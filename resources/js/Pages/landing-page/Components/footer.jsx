import Checkbox from "@/Components/Checkbox";
import PrimaryButton from "@/Components/PrimaryButton";
import TextInput from "@/Components/TextInput";
import { Link, useForm } from "@inertiajs/inertia-react";
import InputError from "@/Components/InputError";
import React, { useEffect, useState } from "react";
import { Inertia } from "@inertiajs/inertia";

const socialmediaData = [
    {
        href: "/home",
        src: "/assets/images/landingpage-img/instagram-icon.svg",
        alt: "instagram icon",
    },
    {
        href: "/home",
        src: "/assets/images/landingpage-img/twitter-icon.svg",
        alt: "twitter icon",
    },
    {
        href: "/home",
        src: "/assets/images/landingpage-img/facebook-icon.svg",
        alt: "facebook icon",
    },
    {
        href: "/home",
        src: "/assets/images/landingpage-img/youtube-icon.svg",
        alt: "youtube icon",
    },
    {
        href: "/home",
        src: "/assets/images/landingpage-img/tiktok-icon.svg",
        alt: "tiktok icon",
    },
    {
        href: "/home",
        src: "/assets/images/landingpage-img/linkedin-icon.svg",
        alt: "linkedin icon",
    },
];

const Footer = () => {
    return (
        <div id="contactus" className="relative">
            <div className="border-t border-[#909191] border-opacity-[0.1] py-10">
                <div className="container">
                    <div className="flex justify-center lg:justify-between items-center flex-wrap gap-7 lg:gap-0">
                        <span className="text-sm font-gilroy-semibold text-[#202322]">
                            © 2023 MyNikahNow Ltd. All rights reserved
                        </span>
                        <ul className="flex gap-4">
                            <li>
                                <Link
                                    href="/privacy-policy"
                                    className="text-sm font-gilroy-semibold text-[#202322] hover:text-themecolor"
                                >
                                    Privacy Policy
                                </Link>
                            </li>
                            <li>
                                <Link
                                    href="/terms-and-conditions"
                                    className="text-sm font-gilroy-semibold text-[#202322] hover:text-themecolor"
                                >
                                    Terms & Conditions
                                </Link>
                            </li>
                        </ul>
                         <ul className="flex gap-5 justify-center flex-wrap">
                            <li>
                                <a
                                    href="https://www.instagram.com/mynikahnow/" target="_blank"
                                    className="bg-white rt-shadow rounded-full w-[50px] h-[50px] flex items-center justify-center"
                                >
                                    <img
                                        src="/assets/images/landingpage-img/instagram-icon.svg"
                                        alt="instagram icon"
                                    />
                                </a>
                            </li>
                            <li>
                                <a
                                    href="https://twitter.com/mynikahnow"  target="_blank"
                                    className="bg-white rt-shadow rounded-full w-[50px] h-[50px] flex items-center justify-center"
                                >
                                    <img
                                        src="/assets/images/landingpage-img/twitter-icon.svg"
                                        alt="twitter icon"
                                    />
                                </a>
                            </li>
                            <li>
                                <a
                                    href="https://www.facebook.com/mynikahnow" target="_blank"
                                    className="bg-white rt-shadow rounded-full w-[50px] h-[50px] flex items-center justify-center"
                                >
                                    <img
                                        src="/assets/images/landingpage-img/facebook-icon.svg"
                                        alt="facebook icon"
                                    />
                                </a>
                            </li>
                            <li>
                                <a
                                    href="https://www.youtube.com/@mynikahnow" target="_blank"
                                    className="bg-white rt-shadow rounded-full w-[50px] h-[50px] flex items-center justify-center"
                                >
                                    <img
                                        src="/assets/images/landingpage-img/youtube-icon.svg"
                                        alt="youtube-icon"
                                    />
                                </a>
                            </li>
                            <li>
                                <a
                                    href="https://www.tiktok.com/@mynikahnow" target="_blank"
                                    className="bg-white rt-shadow rounded-full w-[50px] h-[50px] flex items-center justify-center"
                                >
                                    <img
                                        src="/assets/images/landingpage-img/tiktok-icon.svg"
                                        alt="tiktok icon"
                                    />
                                </a>
                            </li>
                            <li>
                                <a
                                    href="https://www.linkedin.com/company/mynikahnow/" target="_blank"
                                    className="bg-white rt-shadow rounded-full w-[50px] h-[50px] flex items-center justify-center"
                                >
                                    <img
                                        src="/assets/images/landingpage-img/linkedin-icon.svg"
                                        alt="linkedin icon"
                                    />
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default Footer;
