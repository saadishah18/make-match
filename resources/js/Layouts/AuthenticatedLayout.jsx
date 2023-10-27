import React, { useState } from "react";
import ApplicationLogo from "@/Components/ApplicationLogo";
import NavLink from "@/Components/NavLink";
import {Menu, Transition} from '@headlessui/react'
import ResponsiveNavLink from "@/Components/ResponsiveNavLink";
import { Link } from "@inertiajs/inertia-react";
import {
    HiOutlineUserGroup,
    HiOutlineUsers,
    HiOutlineCalendar,
    HiChevronDown,
} from "react-icons/hi";
import { HiOutlineChatBubbleLeftEllipsis } from "react-icons/hi2";
import {
    RiFileList3Line,
    RiFileList2Line,
    RiUserSettingsLine,
} from "react-icons/ri";
import {
    AiOutlineFileProtect,
    AiOutlinePercentage,
    AiOutlineLogout,
} from "react-icons/ai";
import { Fragment } from "react";
// import { ToastContainer } from 'react-toastify';
// import 'react-toastify/dist/ReactToastify.css';

export default function Authenticated({ auth, header, children }) {
    const [showingNavigationDropdown, setShowingNavigationDropdown] = useState(false);
    const [openSidebarMenu, setopenSidebarMenu] = useState(false);
    const check_role = auth.user.roles[0];
    return (
        <div
            className={`wp-wrapper px-[15px] py-[30px] lg:pt-[120px] lg:pr-[15px] lg:pb-[50px] lg:pl-[15px] xl:pt-[120px] xl:pr-[30px] xl:pb-[50px] xl:pl-[330px] 2xl:pl-[400px] ${
                openSidebarMenu ? "at-opensidebar" : ""
            }`}
        >
            <div className="min-h-screen">
                <div className="bg-white shadow fixed top-0 left-0 w-full py-5 px-8 flex justify-end z-10">
                    <Menu as="div" className="relative inline-block text-left">
                        <Menu.Button className="flex items-center gap-2">
                        <span className="bg-themecolor text-white text-[20px] rounded-full font-product_sansregular px-[6px] w-[35px] h-[35px] flex items-center justify-center mx-auto">
                            {auth.user.first_name.charAt(0)+''+auth.user.last_name.charAt(0)}
                        </span>
                        <HiChevronDown />
                        </Menu.Button>
                        <Transition
                            as={Fragment}
                            enter="transition ease-out duration-100"
                            enterFrom="transform opacity-0 scale-95"
                            enterTo="transform opacity-100 scale-100"
                            leave="transition ease-in duration-75"
                            leaveFrom="transform opacity-100 scale-100"
                            leaveTo="transform opacity-0 scale-95"
                        >
                            <Menu.Items
                                className="absolute z-10 right-0 mt-2 w-56 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                                    <Menu.Item>
                                        <Link href={check_role.name == "admin" ? route('profile') : route('profile')}>

                                            <button type="button"
                                                    className="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                                Profile
                                            </button>
                                        </Link>
                                    </Menu.Item>
                                    <Menu.Item>
                                        <Link href={route("logout")}

                                            method="post"
                                            type="button"
                                            className="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"

                                           > Logout
                                        </Link>
                                    </Menu.Item>
                            </Menu.Items>
                        </Transition>
                    </Menu>
                </div>
                {header && (
                    <header className="at-header bg-white shadow fixed top-0 -left-[240px] xl:left-0 w-[240px] lg:w-[240px] xl:w-[300px] 2xl:w-[360px] h-screen at-themescrollhide z-10">
                        <button className="at-menubtn !bg-themecolor block xl:hidden" type="button" onClick={() => setopenSidebarMenu(!openSidebarMenu)}>
                            <img
                                src="/assets/images/svg/menu.svg"
                                alt="Menu Icon"
                            />
                        </button>
                        <strong className="at-dblogo w-full p-9 flex justify-center items-center">
                            <Link href="#"> <img
                                    className="w-full block h-auto"
                                    src="/assets/images/logo.png"
                                />
                            </Link>
                           {/* <Link href="#">
                                <svg
                                    width="60"
                                    height="58"
                                    viewBox="0 0 60 58"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        d="M58.395 41.9046L54.6808 45.8475L52.5916 48.0647C52.0478 48.6291 51.4674 49.1238 50.8854 49.552C49.3517 50.6825 47.808 51.3248 46.9126 51.3248C45.926 51.3248 44.9378 50.3598 45.0605 49.5131C45.4303 48.3064 46.7899 43.9579 47.6538 41.6629L56.4202 18.2339C60.6168 6.88353 57.6538 0.120024 48.5193 0.120024C42.8403 0.120024 38.2723 2.17341 27.0386 9.90197C28.3966 3.86347 27.1613 0 21.9765 0C17.2658 0 12.9614 2.38912 7.14807 8.65145C5.80169 10.1031 4.37407 11.7607 2.84032 13.6454L4.28121 14.8376L4.44537 14.9738L8.22418 10.9757L10.4941 8.5736C12.3462 6.64186 14.6924 5.55516 15.8017 5.55516C16.7899 5.55516 17.9008 6.52022 17.7765 7.36687C17.4067 8.5736 15.9244 12.922 15.0605 15.2171L0 55.6716L8.92224 52.8235L11.7277 51.9282C16.2229 39.663 20.8324 27.5049 25.4452 15.2495C25.6475 14.7143 25.8465 14.1823 26.0487 13.647C33.9496 8.21353 37.1597 6.76351 41.2336 6.76351C47.2824 6.76351 48.8874 10.507 45.9261 18.4772L37.6538 40.8195C34.1967 49.9981 33.3328 57.0016 40.6168 57.0016C44.3492 57.0016 47.7052 55.6165 51.6714 52.178C54.1336 50.0435 56.8297 47.1207 60 43.2346L58.395 41.9062V41.9046Z"
                                        fill="url(#paint0_linear_514_196)"
                                    />
                                    <defs>
                                        <linearGradient
                                            id="paint0_linear_514_196"
                                            x1="30"
                                            y1="0"
                                            x2="30"
                                            y2="57.0016"
                                            gradientUnits="userSpaceOnUse"
                                        >
                                            <stop stop-color="#BE2D87"/>
                                            <stop offset="1" stop-color="#F74989"/>
                                        </linearGradient>
                                    </defs>
                                </svg>
                            </Link>*/}
                        </strong>
                        <div className="w-full pb-11 px-5 flex items-center justify-center flex-col">
                            <h6 className="text-black font-product_sansregular tracking-wide font-bold flex items-center gap-2">
                                {auth.user.name}
                                {/*<Link href="/settings" className="">*/}
                                {/*    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">*/}
                                {/*        <path d="M6.53118 16.0199L6.25825 15.3213L6.53118 16.0199ZM2.47478 16.7988L2.09978 17.4483L2.09978 17.4483L2.47478 16.7988ZM1.12116 12.8964L0.379715 13.0093L1.12116 12.8964ZM1.61146 10.2941L2.26098 10.6691L1.61146 10.2941ZM1.02731 11.5314L0.290281 11.3925H0.290281L1.02731 11.5314ZM8.53967 14.2941L9.18918 14.6691L8.53967 14.2941ZM7.76024 15.4186L8.24902 15.9875H8.24902L7.76024 15.4186ZM5.4099 3.71503L4.76038 3.34003L5.4099 3.71503ZM16.4099 18.0009C16.8241 18.0009 17.1599 17.6651 17.1599 17.2509C17.1599 16.8367 16.8241 16.5009 16.4099 16.5009V18.0009ZM10.4099 16.5009C9.99569 16.5009 9.6599 16.8367 9.6599 17.2509C9.6599 17.6651 9.99569 18.0009 10.4099 18.0009V16.5009ZM11.6886 7.34003L7.89015 13.9191L9.18918 14.6691L12.9876 8.09003L11.6886 7.34003ZM2.26098 10.6691L6.05942 4.09003L4.76038 3.34003L0.961943 9.91912L2.26098 10.6691ZM6.25825 15.3213C5.16178 15.7497 4.41502 16.0394 3.83854 16.1741C3.28167 16.3042 3.02898 16.2527 2.84978 16.1493L2.09978 17.4483C2.75305 17.8255 3.45392 17.8044 4.17981 17.6348C4.88609 17.4698 5.75129 17.1298 6.80411 16.7184L6.25825 15.3213ZM0.379715 13.0093C0.549904 14.1267 0.688048 15.046 0.898285 15.7402C1.11436 16.4536 1.44651 17.0712 2.09978 17.4483L2.84978 16.1493C2.67059 16.0458 2.49965 15.8527 2.33389 15.3054C2.16229 14.7388 2.03986 13.9472 1.86261 12.7835L0.379715 13.0093ZM0.961943 9.91912C0.640122 10.4765 0.382457 10.9033 0.290281 11.3925L1.76434 11.6702C1.7983 11.49 1.88802 11.3151 2.26098 10.6691L0.961943 9.91912ZM1.86261 12.7835C1.7503 12.046 1.73039 11.8505 1.76434 11.6702L0.290281 11.3925C0.198105 11.8817 0.282803 12.373 0.379715 13.0093L1.86261 12.7835ZM7.89015 13.9191C7.51719 14.5651 7.41055 14.7303 7.27146 14.8498L8.24902 15.9875C8.62661 15.6631 8.86736 15.2265 9.18918 14.6691L7.89015 13.9191ZM6.80411 16.7184C7.40362 16.4842 7.87142 16.3119 8.24902 15.9875L7.27146 14.8498C7.13237 14.9693 6.95303 15.0498 6.25825 15.3213L6.80411 16.7184ZM10.499 2.90045C11.3339 3.38245 11.8939 3.70761 12.2797 4.00537C12.6483 4.28983 12.7658 4.48144 12.8135 4.65945L14.2623 4.27123C14.0956 3.64904 13.6976 3.20485 13.1961 2.81785C12.7119 2.44416 12.0471 2.06221 11.249 1.60141L10.499 2.90045ZM12.9876 8.09003C13.4484 7.29189 13.8331 6.62875 14.0657 6.06299C14.3065 5.47711 14.4291 4.89341 14.2623 4.27123L12.8135 4.65945C12.8612 4.83747 12.8553 5.06212 12.6783 5.49278C12.493 5.94357 12.1706 6.50517 11.6886 7.34003L12.9876 8.09003ZM11.249 1.60141C10.4509 1.1406 9.78772 0.755898 9.22197 0.523373C8.63608 0.282573 8.05238 0.159968 7.4302 0.326681L7.81843 1.77557C7.99644 1.72787 8.22109 1.73376 8.65175 1.91076C9.10254 2.09604 9.66414 2.41844 10.499 2.90045L11.249 1.60141ZM6.05942 4.09003C6.54142 3.25517 6.86658 2.69516 7.16434 2.30931C7.4488 1.9407 7.64041 1.82327 7.81843 1.77557L7.4302 0.326681C6.80801 0.493395 6.36382 0.891423 5.97683 1.39291C5.60313 1.87716 5.22118 2.54189 4.76038 3.34003L6.05942 4.09003ZM12.7131 7.06551L5.7849 3.06551L5.0349 4.36455L11.9631 8.36455L12.7131 7.06551ZM16.4099 16.5009H10.4099V18.0009H16.4099V16.5009Z" fill="#BE2D87" />*/}
                                {/*    </svg>*/}
                                {/*</Link>*/}
                            </h6>
                            {check_role.name == "admin" ? (
                                <span className="block mt-2 font-product_sansregular flex items-center text-black">
                                    <strong className="mr-2">Role:</strong>{" "}
                                    Admin
                                </span>
                            ) : (
                                <span className="block mt-2 font-product_sansregular flex items-center text-black">
                                    <strong className="mr-2">Role:</strong> Imam
                                </span>
                            )}
                        </div>
                        <nav className="w-full float-left nav-scroll">
                            <div className="w-full float-left">
                                <div className="flex justify-between">
                                    {check_role.name == "admin" ? (
                                        <div className="flex w-full flex-col">
                                            <NavLink
                                                className="min-h-[60px] bg-themecolor "
                                                active={
                                                    route().current() === "nikahmanagement" ? true : false
                                                }
                                                href={route("nikahmanagement")}
                                                as="button"
                                            >
                                                {/* <img src="/assets/images/svg/dashboard.svg" alt="Dashboard Icon" /> */}
                                                <HiOutlineUserGroup className="text-xl" />
                                                <span>Nikah Management</span>
                                            </NavLink>
                                            <NavLink
                                                className="mt-2 min-h-[60px]"
                                                active={
                                                    route().current() ===
                                                    "eventscalendar"
                                                }
                                                href={route("eventscalendar")}
                                                as="button"
                                            >
                                                {/* <img src="/assets/images/svg/dashboard.svg" alt="Dashboard Icon" /> */}
                                                {/*<svg width="20" height="18" viewBox="0 0 20 18" fill="none" xmlns="http://www.w3.org/2000/svg">*/}
                                                {/*    <path fillRule="evenodd" clipRule="evenodd" d="M12.941 1C12.941 0.447715 12.4933 0 11.941 0H8.05869C7.5064 0 7.05869 0.447716 7.05869 1V17.6471H12.941V1ZM11.441 16.1471V1.5H8.55869V16.1471H11.441ZM14.441 9.67556C14.2423 9.85833 14.1178 10.1205 14.1178 10.4117V17.647H20.0002V10.4117C20.0002 9.85945 19.5525 9.41174 19.0002 9.41174H15.1178C14.8568 9.41174 14.6191 9.51176 14.441 9.67556ZM18.5002 16.147V10.9117H15.6178V16.147H18.5002ZM5.88235 17.6471V6.88239C5.88235 6.59092 5.75765 6.32857 5.55869 6.14579C5.38065 5.98224 5.14316 5.88239 4.88235 5.88239H1C0.447715 5.88239 0 6.33011 0 6.88239V17.6471H5.88235ZM4.38235 16.1471H1.5V7.38239H4.38235V16.1471Z" fill="#3A3A3A" />*/}
                                                {/*</svg>*/}
                                                <HiOutlineCalendar className="text-xl" />
                                                <span>Events Calendar</span>
                                            </NavLink>
                                            <NavLink
                                                className="mt-2 min-h-[60px]"
                                                active={
                                                    route().current() === "talaq" ? true : false
                                                }
                                                href={route("talaq")}
                                                as="button"
                                            >
                                                {/* <img src="/assets/images/svg/dashboard.svg" alt="Dashboard Icon" /> */}
                                                {/*<svg width="20" height="18" viewBox="0 0 20 18" fill="none" xmlns="http://www.w3.org/2000/svg">*/}
                                                {/*    <path fillRule="evenodd" clipRule="evenodd" d="M12.941 1C12.941 0.447715 12.4933 0 11.941 0H8.05869C7.5064 0 7.05869 0.447716 7.05869 1V17.6471H12.941V1ZM11.441 16.1471V1.5H8.55869V16.1471H11.441ZM14.441 9.67556C14.2423 9.85833 14.1178 10.1205 14.1178 10.4117V17.647H20.0002V10.4117C20.0002 9.85945 19.5525 9.41174 19.0002 9.41174H15.1178C14.8568 9.41174 14.6191 9.51176 14.441 9.67556ZM18.5002 16.147V10.9117H15.6178V16.147H18.5002ZM5.88235 17.6471V6.88239C5.88235 6.59092 5.75765 6.32857 5.55869 6.14579C5.38065 5.98224 5.14316 5.88239 4.88235 5.88239H1C0.447715 5.88239 0 6.33011 0 6.88239V17.6471H5.88235ZM4.38235 16.1471H1.5V7.38239H4.38235V16.1471Z" fill="#3A3A3A" />*/}
                                                {/*</svg>*/}
                                                <RiFileList3Line className="text-lg" />
                                                <span>Talaq Management</span>
                                            </NavLink>
                                            <NavLink
                                                className="mt-2 min-h-[60px]"
                                                active={
                                                    route().current() === "khula" ? true : false
                                                }
                                                href={route("khula")}
                                                as="button"
                                            >
                                                {/* <img src="/assets/images/svg/dashboard.svg" alt="Dashboard Icon" /> */}
                                                {/*<svg width="20" height="18" viewBox="0 0 20 18" fill="none" xmlns="http://www.w3.org/2000/svg">*/}
                                                {/*    <path fillRule="evenodd" clipRule="evenodd" d="M12.941 1C12.941 0.447715 12.4933 0 11.941 0H8.05869C7.5064 0 7.05869 0.447716 7.05869 1V17.6471H12.941V1ZM11.441 16.1471V1.5H8.55869V16.1471H11.441ZM14.441 9.67556C14.2423 9.85833 14.1178 10.1205 14.1178 10.4117V17.647H20.0002V10.4117C20.0002 9.85945 19.5525 9.41174 19.0002 9.41174H15.1178C14.8568 9.41174 14.6191 9.51176 14.441 9.67556ZM18.5002 16.147V10.9117H15.6178V16.147H18.5002ZM5.88235 17.6471V6.88239C5.88235 6.59092 5.75765 6.32857 5.55869 6.14579C5.38065 5.98224 5.14316 5.88239 4.88235 5.88239H1C0.447715 5.88239 0 6.33011 0 6.88239V17.6471H5.88235ZM4.38235 16.1471H1.5V7.38239H4.38235V16.1471Z" fill="#3A3A3A" />*/}
                                                {/*</svg>*/}
                                                <RiFileList2Line className="text-xl" />
                                                <span>Khula Management</span>
                                            </NavLink>
                                            <NavLink
                                                className="mt-2 min-h-[60px]"
                                                active={
                                                    route().current() === "ruju" ? true : false
                                                }
                                                href={route("ruju")}
                                                as="button"
                                            >
                                                {/* <img src="/assets/images/svg/dashboard.svg" alt="Dashboard Icon" /> */}
                                                {/*<svg width="20" height="18" viewBox="0 0 20 18" fill="none" xmlns="http://www.w3.org/2000/svg">*/}
                                                {/*    <path fillRule="evenodd" clipRule="evenodd" d="M12.941 1C12.941 0.447715 12.4933 0 11.941 0H8.05869C7.5064 0 7.05869 0.447716 7.05869 1V17.6471H12.941V1ZM11.441 16.1471V1.5H8.55869V16.1471H11.441ZM14.441 9.67556C14.2423 9.85833 14.1178 10.1205 14.1178 10.4117V17.647H20.0002V10.4117C20.0002 9.85945 19.5525 9.41174 19.0002 9.41174H15.1178C14.8568 9.41174 14.6191 9.51176 14.441 9.67556ZM18.5002 16.147V10.9117H15.6178V16.147H18.5002ZM5.88235 17.6471V6.88239C5.88235 6.59092 5.75765 6.32857 5.55869 6.14579C5.38065 5.98224 5.14316 5.88239 4.88235 5.88239H1C0.447715 5.88239 0 6.33011 0 6.88239V17.6471H5.88235ZM4.38235 16.1471H1.5V7.38239H4.38235V16.1471Z" fill="#3A3A3A" />*/}
                                                {/*</svg>*/}
                                                <AiOutlineFileProtect className="text-xl" />
                                                <span>Ruju Management</span>
                                            </NavLink>
                                            <NavLink
                                                className="mt-2 min-h-[60px]"
                                                active={
                                                    route().current() === "imams" ? true : false
                                                }
                                                href={route("imams")}
                                                as="button"
                                            >
                                                {/* <img src="/assets/images/svg/dashboard.svg" alt="Dashboard Icon" /> */}
                                                {/*<svg width="20" height="18" viewBox="0 0 20 18" fill="none" xmlns="http://www.w3.org/2000/svg">*/}
                                                {/*    <path fillRule="evenodd" clipRule="evenodd" d="M12.941 1C12.941 0.447715 12.4933 0 11.941 0H8.05869C7.5064 0 7.05869 0.447716 7.05869 1V17.6471H12.941V1ZM11.441 16.1471V1.5H8.55869V16.1471H11.441ZM14.441 9.67556C14.2423 9.85833 14.1178 10.1205 14.1178 10.4117V17.647H20.0002V10.4117C20.0002 9.85945 19.5525 9.41174 19.0002 9.41174H15.1178C14.8568 9.41174 14.6191 9.51176 14.441 9.67556ZM18.5002 16.147V10.9117H15.6178V16.147H18.5002ZM5.88235 17.6471V6.88239C5.88235 6.59092 5.75765 6.32857 5.55869 6.14579C5.38065 5.98224 5.14316 5.88239 4.88235 5.88239H1C0.447715 5.88239 0 6.33011 0 6.88239V17.6471H5.88235ZM4.38235 16.1471H1.5V7.38239H4.38235V16.1471Z" fill="#3A3A3A" />*/}
                                                {/*</svg>*/}
                                                <RiUserSettingsLine className="text-xl" />
                                                <span>Imams Management</span>
                                            </NavLink>
                                            <NavLink
                                                className="mt-2 min-h-[60px]"
                                                active={
                                                    route().current() ===
                                                    "witness.index" ? true : false
                                                }
                                                href={route("witness.index")}
                                                as="button"
                                            >
                                                {/* <img src="/assets/images/svg/dashboard.svg" alt="Dashboard Icon" /> */}
                                                {/*<svg width="20" height="18" viewBox="0 0 20 18" fill="none" xmlns="http://www.w3.org/2000/svg">*/}
                                                {/*    <path fillRule="evenodd" clipRule="evenodd" d="M12.941 1C12.941 0.447715 12.4933 0 11.941 0H8.05869C7.5064 0 7.05869 0.447716 7.05869 1V17.6471H12.941V1ZM11.441 16.1471V1.5H8.55869V16.1471H11.441ZM14.441 9.67556C14.2423 9.85833 14.1178 10.1205 14.1178 10.4117V17.647H20.0002V10.4117C20.0002 9.85945 19.5525 9.41174 19.0002 9.41174H15.1178C14.8568 9.41174 14.6191 9.51176 14.441 9.67556ZM18.5002 16.147V10.9117H15.6178V16.147H18.5002ZM5.88235 17.6471V6.88239C5.88235 6.59092 5.75765 6.32857 5.55869 6.14579C5.38065 5.98224 5.14316 5.88239 4.88235 5.88239H1C0.447715 5.88239 0 6.33011 0 6.88239V17.6471H5.88235ZM4.38235 16.1471H1.5V7.38239H4.38235V16.1471Z" fill="#3A3A3A" />*/}
                                                {/*</svg>*/}
                                                <HiOutlineUsers className="text-xl" />
                                                <span>Witnesses Library</span>
                                            </NavLink>
                                            <NavLink className="mt-2 min-h-[60px]"
                                                active={route().current() === "users" ? true : false}
                                                href={route("users")}
                                                as="button"
                                            >
                                                {/* <img src="/assets/images/svg/dashboard.svg" alt="Dashboard Icon" /> */}
                                                {/*<svg width="20" height="18" viewBox="0 0 20 18" fill="none" xmlns="http://www.w3.org/2000/svg">*/}
                                                {/*    <path fillRule="evenodd" clipRule="evenodd" d="M12.941 1C12.941 0.447715 12.4933 0 11.941 0H8.05869C7.5064 0 7.05869 0.447716 7.05869 1V17.6471H12.941V1ZM11.441 16.1471V1.5H8.55869V16.1471H11.441ZM14.441 9.67556C14.2423 9.85833 14.1178 10.1205 14.1178 10.4117V17.647H20.0002V10.4117C20.0002 9.85945 19.5525 9.41174 19.0002 9.41174H15.1178C14.8568 9.41174 14.6191 9.51176 14.441 9.67556ZM18.5002 16.147V10.9117H15.6178V16.147H18.5002ZM5.88235 17.6471V6.88239C5.88235 6.59092 5.75765 6.32857 5.55869 6.14579C5.38065 5.98224 5.14316 5.88239 4.88235 5.88239H1C0.447715 5.88239 0 6.33011 0 6.88239V17.6471H5.88235ZM4.38235 16.1471H1.5V7.38239H4.38235V16.1471Z" fill="#3A3A3A" />*/}
                                                {/*</svg>*/}
                                                <AiOutlinePercentage className="text-xl" />
                                                <span>Users</span>
                                            </NavLink>
                                            <NavLink
                                                className="mt-2 min-h-[60px]"
                                                active={
                                                    route().current() === "vat" ? true : false
                                                }
                                                href={route("vat")}
                                                as="button"
                                            >
                                                {/* <img src="/assets/images/svg/dashboard.svg" alt="Dashboard Icon" /> */}
                                                {/*<svg width="20" height="18" viewBox="0 0 20 18" fill="none" xmlns="http://www.w3.org/2000/svg">*/}
                                                {/*    <path fillRule="evenodd" clipRule="evenodd" d="M12.941 1C12.941 0.447715 12.4933 0 11.941 0H8.05869C7.5064 0 7.05869 0.447716 7.05869 1V17.6471H12.941V1ZM11.441 16.1471V1.5H8.55869V16.1471H11.441ZM14.441 9.67556C14.2423 9.85833 14.1178 10.1205 14.1178 10.4117V17.647H20.0002V10.4117C20.0002 9.85945 19.5525 9.41174 19.0002 9.41174H15.1178C14.8568 9.41174 14.6191 9.51176 14.441 9.67556ZM18.5002 16.147V10.9117H15.6178V16.147H18.5002ZM5.88235 17.6471V6.88239C5.88235 6.59092 5.75765 6.32857 5.55869 6.14579C5.38065 5.98224 5.14316 5.88239 4.88235 5.88239H1C0.447715 5.88239 0 6.33011 0 6.88239V17.6471H5.88235ZM4.38235 16.1471H1.5V7.38239H4.38235V16.1471Z" fill="#3A3A3A" />*/}
                                                {/*</svg>*/}
                                                <AiOutlinePercentage className="text-xl" />
                                                <span>VAT</span>
                                            </NavLink>
                                            <NavLink
                                                className="mt-2 min-h-[60px]"
                                                active={
                                                    route().current() === "contact-users-listing" ? true : false
                                                }
                                                href={route("getContactUsers")}
                                                as="button"
                                            >
                                                {/* <img src="/assets/images/svg/dashboard.svg" alt="Dashboard Icon" /> */}
                                                {/*<svg width="20" height="18" viewBox="0 0 20 18" fill="none" xmlns="http://www.w3.org/2000/svg">*/}
                                                {/*    <path fillRule="evenodd" clipRule="evenodd" d="M12.941 1C12.941 0.447715 12.4933 0 11.941 0H8.05869C7.5064 0 7.05869 0.447716 7.05869 1V17.6471H12.941V1ZM11.441 16.1471V1.5H8.55869V16.1471H11.441ZM14.441 9.67556C14.2423 9.85833 14.1178 10.1205 14.1178 10.4117V17.647H20.0002V10.4117C20.0002 9.85945 19.5525 9.41174 19.0002 9.41174H15.1178C14.8568 9.41174 14.6191 9.51176 14.441 9.67556ZM18.5002 16.147V10.9117H15.6178V16.147H18.5002ZM5.88235 17.6471V6.88239C5.88235 6.59092 5.75765 6.32857 5.55869 6.14579C5.38065 5.98224 5.14316 5.88239 4.88235 5.88239H1C0.447715 5.88239 0 6.33011 0 6.88239V17.6471H5.88235ZM4.38235 16.1471H1.5V7.38239H4.38235V16.1471Z" fill="#3A3A3A" />*/}
                                                {/*</svg>*/}
                                                <AiOutlinePercentage className="text-xl" />
                                                <span>Contact Form Users</span>
                                            </NavLink>
                                            <NavLink
                                                className="mt-2 min-h-[60px]"
                                                active={
                                                    route().current() === "servicesOffered" ? true : false
                                                }
                                                href={route("servicesOffered")}
                                                as="button"
                                            >
                                                {/* <img src="/assets/images/svg/dashboard.svg" alt="Dashboard Icon" /> */}
                                                {/*<svg width="20" height="18" viewBox="0 0 20 18" fill="none" xmlns="http://www.w3.org/2000/svg">*/}
                                                {/*    <path fillRule="evenodd" clipRule="evenodd" d="M12.941 1C12.941 0.447715 12.4933 0 11.941 0H8.05869C7.5064 0 7.05869 0.447716 7.05869 1V17.6471H12.941V1ZM11.441 16.1471V1.5H8.55869V16.1471H11.441ZM14.441 9.67556C14.2423 9.85833 14.1178 10.1205 14.1178 10.4117V17.647H20.0002V10.4117C20.0002 9.85945 19.5525 9.41174 19.0002 9.41174H15.1178C14.8568 9.41174 14.6191 9.51176 14.441 9.67556ZM18.5002 16.147V10.9117H15.6178V16.147H18.5002ZM5.88235 17.6471V6.88239C5.88235 6.59092 5.75765 6.32857 5.55869 6.14579C5.38065 5.98224 5.14316 5.88239 4.88235 5.88239H1C0.447715 5.88239 0 6.33011 0 6.88239V17.6471H5.88235ZM4.38235 16.1471H1.5V7.38239H4.38235V16.1471Z" fill="#3A3A3A" />*/}
                                                {/*</svg>*/}
                                                <AiOutlinePercentage className="text-xl" />
                                                <span>Service Offered</span>
                                            </NavLink>
                                            <NavLink
                                                className="mt-2 min-h-[60px]"
                                                active={
                                                    route().current() === "nikahTypes" ? true : false
                                                }
                                                href={route("nikahTypes")}
                                                as="button"
                                            >
                                                {/* <img src="/assets/images/svg/dashboard.svg" alt="Dashboard Icon" /> */}
                                                {/*<svg width="20" height="18" viewBox="0 0 20 18" fill="none" xmlns="http://www.w3.org/2000/svg">*/}
                                                {/*    <path fillRule="evenodd" clipRule="evenodd" d="M12.941 1C12.941 0.447715 12.4933 0 11.941 0H8.05869C7.5064 0 7.05869 0.447716 7.05869 1V17.6471H12.941V1ZM11.441 16.1471V1.5H8.55869V16.1471H11.441ZM14.441 9.67556C14.2423 9.85833 14.1178 10.1205 14.1178 10.4117V17.647H20.0002V10.4117C20.0002 9.85945 19.5525 9.41174 19.0002 9.41174H15.1178C14.8568 9.41174 14.6191 9.51176 14.441 9.67556ZM18.5002 16.147V10.9117H15.6178V16.147H18.5002ZM5.88235 17.6471V6.88239C5.88235 6.59092 5.75765 6.32857 5.55869 6.14579C5.38065 5.98224 5.14316 5.88239 4.88235 5.88239H1C0.447715 5.88239 0 6.33011 0 6.88239V17.6471H5.88235ZM4.38235 16.1471H1.5V7.38239H4.38235V16.1471Z" fill="#3A3A3A" />*/}
                                                {/*</svg>*/}
                                                <AiOutlinePercentage className="text-xl" />
                                                <span>Nikah Types</span>
                                            </NavLink>
                                            <NavLink
                                                className="mt-2 min-h-[60px]"
                                                active={
                                                    route().current() === "chat" ? true : false
                                                }
                                                href={route("chat")}
                                                as="button"
                                            >
                                                <HiOutlineChatBubbleLeftEllipsis className="text-xl" />
                                                <span>Chat</span>
                                            </NavLink>
                                            <NavLink className="mt-2 min-h-[60px] " active={route().current() === "getPrivacyPolicy" ? true : false}
                                             href={route("getPrivacyPolicy")}
                                                as="button"
                                            >
                                                <RiFileList3Line className="text-lg shrink-0" />
                                                <span>Privacy Policy / Terms And conditions</span>
                                            </NavLink>
                                            {/*<NavLink
                                                method="post"
                                                href={route("logout")}
                                                as="button"
                                                className="mt-2 min-h-[60px]"
                                            >
                                                <AiOutlineLogout />
                                                Logout
                                            </NavLink>*/}
                                        </div>
                                    ) : (
                                        <div className="flex w-full flex-col">
                                            <NavLink
                                                className="min-h-[60px] bg-themecolor"
                                                active={route().current() === "imam.nikahmanagement" ? true : false}
                                                href={route("imam.nikahmanagement")}
                                                // as="button"
                                            >
                                                {/* <img src="/assets/images/svg/dashboard.svg" alt="Dashboard Icon" /> */}
                                                <HiOutlineUserGroup className="text-xl" />
                                                <span>Nikah Management</span>
                                            </NavLink>
                                            <NavLink
                                                className="mt-2 min-h-[60px]"
                                                active={route().current() === "imam.khula" ? true : false}
                                                href={route("imam.khula")}
                                            >
                                                {/* <img src="/assets/images/svg/dashboard.svg" alt="Dashboard Icon" /> */}
                                                {/*<svg width="20" height="18" viewBox="0 0 20 18" fill="none" xmlns="http://www.w3.org/2000/svg">*/}
                                                {/*    <path fillRule="evenodd" clipRule="evenodd" d="M12.941 1C12.941 0.447715 12.4933 0 11.941 0H8.05869C7.5064 0 7.05869 0.447716 7.05869 1V17.6471H12.941V1ZM11.441 16.1471V1.5H8.55869V16.1471H11.441ZM14.441 9.67556C14.2423 9.85833 14.1178 10.1205 14.1178 10.4117V17.647H20.0002V10.4117C20.0002 9.85945 19.5525 9.41174 19.0002 9.41174H15.1178C14.8568 9.41174 14.6191 9.51176 14.441 9.67556ZM18.5002 16.147V10.9117H15.6178V16.147H18.5002ZM5.88235 17.6471V6.88239C5.88235 6.59092 5.75765 6.32857 5.55869 6.14579C5.38065 5.98224 5.14316 5.88239 4.88235 5.88239H1C0.447715 5.88239 0 6.33011 0 6.88239V17.6471H5.88235ZM4.38235 16.1471H1.5V7.38239H4.38235V16.1471Z" fill="#3A3A3A" />*/}
                                                {/*</svg>*/}
                                                <RiFileList2Line className="text-xl" />
                                                <span>Khula Management</span>
                                            </NavLink>
                                            <NavLink
                                                className="mt-2 min-h-[60px]"
                                                active={route().current() === "imam.event-scheduler" ? true : false}
                                                href={route("imam.event-scheduler")}
                                                // as="button"
                                            >
                                                {/* <img src="/assets/images/svg/dashboard.svg" alt="Dashboard Icon" /> */}
                                                {/*<svg width="20" height="18" viewBox="0 0 20 18" fill="none" xmlns="http://www.w3.org/2000/svg">*/}
                                                {/*    <path fillRule="evenodd" clipRule="evenodd" d="M12.941 1C12.941 0.447715 12.4933 0 11.941 0H8.05869C7.5064 0 7.05869 0.447716 7.05869 1V17.6471H12.941V1ZM11.441 16.1471V1.5H8.55869V16.1471H11.441ZM14.441 9.67556C14.2423 9.85833 14.1178 10.1205 14.1178 10.4117V17.647H20.0002V10.4117C20.0002 9.85945 19.5525 9.41174 19.0002 9.41174H15.1178C14.8568 9.41174 14.6191 9.51176 14.441 9.67556ZM18.5002 16.147V10.9117H15.6178V16.147H18.5002ZM5.88235 17.6471V6.88239C5.88235 6.59092 5.75765 6.32857 5.55869 6.14579C5.38065 5.98224 5.14316 5.88239 4.88235 5.88239H1C0.447715 5.88239 0 6.33011 0 6.88239V17.6471H5.88235ZM4.38235 16.1471H1.5V7.38239H4.38235V16.1471Z" fill="#3A3A3A" />*/}
                                                {/*</svg>*/}
                                                <HiOutlineCalendar className="text-xl" />
                                                <span>Date & Time schedule</span>
                                            </NavLink>
                                           {/* <ResponsiveNavLink
                                                method="post"
                                                href={route("logout")}
                                                as="button"
                                                className="mt-2 min-h-[60px]"
                                            >
                                                <AiOutlineLogout />
                                                Log Out
                                            </ResponsiveNavLink>*/}
                                        </div>
                                    )}
                                    <div className="-mr-2 flex items-center sm:hidden">
                                        <button
                                            onClick={() =>
                                                setShowingNavigationDropdown(
                                                    (previousState) =>
                                                        !previousState
                                                )
                                            }
                                            className="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                                        >
                                            <svg
                                                className="h-6 w-6"
                                                stroke="currentColor"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    className={
                                                        !showingNavigationDropdown
                                                            ? "inline-flex"
                                                            : "hidden"
                                                    }
                                                    strokeLinecap="round"
                                                    strokeLinejoin="round"
                                                    strokeWidth="2"
                                                    d="M4 6h16M4 12h16M4 18h16"
                                                />
                                                <path
                                                    className={
                                                        showingNavigationDropdown
                                                            ? "inline-flex"
                                                            : "hidden"
                                                    }
                                                    strokeLinecap="round"
                                                    strokeLinejoin="round"
                                                    strokeWidth="2"
                                                    d="M6 18L18 6M6 6l12 12"
                                                />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div className={(showingNavigationDropdown ? "block" : "hidden") + " sm:hidden"}>
                                <div className="pt-2 pb-3 space-y-1">
                                    <ResponsiveNavLink
                                        href={route("nikahmanagement")}
                                        active={route().current() === "nikahmanagement" ? true : false}
                                    >
                                        nikahmanagement
                                    </ResponsiveNavLink>
                                </div>

                                <div className="pt-4 pb-1 border-t border-gray-200">
                                    <div className="px-4">
                                        <div className="font-medium text-base text-gray-800">
                                            {auth.user.name}
                                        </div>
                                        <div className="font-medium text-sm text-gray-500">
                                            {auth.user.email}
                                        </div>
                                    </div>

                                    {/*<div className="mt-3 space-y-1">
                                        <ResponsiveNavLink
                                            method="post"
                                            href={route("logout")}
                                            as="button"
                                        >
                                            <AiOutlineLogout />
                                            Log Out
                                        </ResponsiveNavLink>
                                    </div>*/}
                                </div>
                            </div>
                        </nav>
                    </header>
                )}
                <main className="w-full clear-both">{children}</main>
                {/*<ToastContainer />*/}
            </div>
        </div>
    );
}
