import React from "react";
import { Link } from "@inertiajs/inertia-react";
import TextInput from "@/Components/TextInput";
import InputError from "@/Components/InputError";
import Checkbox from "@/Components/Checkbox";
import {useState} from "react";
import MailchimpSubscribe from "react-mailchimp-subscribe";
import ContactusForm from "@/Pages/landing-page/Components/ContactusForm";
import NotifyMe from "@/Pages/landing-page/Components/NotifyMe";
const postUrl = `https://mynikahnow.us9.list-manage.com/subscribe/post?u=eb3d0044cb556115fc6b9c235&id=f19b294edf&f_id=000908e1f0`;

const Banner = () => {
    const [show, setShow] = useState(false);


    return (
        <div className="rt-bannerbg-img pt-[11.25rem] pb-[5.438rem]" id="banner">
            <div className="container">
                <div className="grid sm:grid-cols-2 items-center gap-24 xl:gap-48">
                    <div className="relative z-[1] after:hidden xl:after:block after:z-[-1] after:w-[104px] after:h-[121px] after:absolute] after:bg-repeat-none after:right-[40px] after:bottom-[-20px]">
                        <h1 className="text-[2.7rem] md:text-[3.3rem] lg:text-[4.5rem] xl:text-[5rem] font-gilroy-regular leading-[4rem] md:leading-[5rem] lg:leading-[5.5rem] xl:leading-[5.813rem] text-white mb-4">
                            The {" "}
                            <span className="font-gilroy-bold">App </span> for
                            <span className="font-gilroy-bold">
                                {" "}
                                Online Nikah
                            </span>
                        </h1>
                        <p className="font-gilroy-medium text-22 text-whitelight mb-14">
                            MyNikahNow will soon enable Muslims worldwide to
                            perform their Nikah Online: easy, quick and halal.
                            Please subscribe to our Newsletter to be informed as
                            soon as we go live.
                        </p>
                        <div className=" rounded-[20px] shadow-[0px_30px_60px_rgba(0,_0,_0,_0.1)] 2xs:px-5 2xs:pt-[30px] pt-[20px] pb-[20px] px-5">


                            <MailchimpSubscribe url={postUrl}
                                render={({ subscribe, status, message}) => {
                                    // console.log(status, message)
                                    return <NotifyMe
                                        // posturl={posturl}
                                        status={status}
                                        message={message}
                                        submitNotifyMeForm={async(data) => await subscribe(data)}
                                    />
                                }}
                            />

                        </div>
             {/*           <br />
                        <br />
                        <div className="flex 2xs:flex-col xs:flex-row items-center gap-4">
                            <Link
                                href="/home"
                                className="flex items-center border border-white border-opacity-30 rounded-2xl py-3 2xs:w-full min-w-[177px] justify-center"
                            >
                                <img
                                    src="/assets/images/apple-icon.svg"
                                    alt="app image"
                                    className="mr-3 w-8 h-8"
                                />
                                <div className="flex flex-col">
                                    <span className="flex text-[10px] text-white text-opacity-70 font-gilroy-regular">
                                        Download on the
                                    </span>
                                    <strong className="text-base text-white font-gilroy-semibold">
                                        App Store
                                    </strong>
                                </div>
                            </Link>
                            <Link
                                href="/home"
                                className="flex items-center border border-white border-opacity-30 rounded-2xl py-3 2xs:w-full min-w-[177px] justify-center"
                            >
                                <img
                                    src="/assets/images/playstore-Icon.svg"
                                    alt="app image"
                                    className="mr-3 w-8 h-8"
                                />
                                <div className="">
                                    <span className="flex text-[10px] text-white text-opacity-70 font-gilroy-regular">
                                        Download on the
                                    </span>
                                    <strong className="text-base text-white font-gilroy-semibold">
                                        Google Play
                                    </strong>
                                </div>
                            </Link>
                        </div>*/}
                    </div>
                    <div className="hidden sm:block">
                        <img
                            src="/assets/images/landingpage-img/banner-img.png"
                            alt="banner image"
                        />
                    </div>
                </div>
            </div>
        </div>
    );
};

export default Banner;
