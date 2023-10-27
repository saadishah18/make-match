import Checkbox from "@/Components/Checkbox";
import PrimaryButton from "@/Components/PrimaryButton";
import TextInput from "@/Components/TextInput";
import { Link, useForm } from "@inertiajs/inertia-react";
import InputError from "@/Components/InputError";
import React, { useEffect, useState } from "react";
import { Inertia } from "@inertiajs/inertia";
import {toast} from "react-toastify";
import MailChimp from "./Mailchimp";
import MailchimpSubscribe from "react-mailchimp-subscribe"
import ContactusForm from "./ContactusForm";

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

const ContactUs = () => {
    // const { data, setData, post, processing, errors, reset } = useForm({
    //     email: "",
    //     firstname: "",
    //     lastname: "",
    //     message: "",
    // });
    // const [show, setShow] = useState(false);
    // const [message, setMessage] = useState(false);

    // const onHandleChange = (event) => {
    //     setData(
    //         event.target.name,
    //         event.target.type === "checkbox"
    //             ? event.target.checked
    //             : event.target.value
    //     );
    // };

    // const onSubmit = async (e) => {
    //     try {
    //         e.preventDefault();
    //         if (
    //             data.email == "" ||
    //             data.firstname == "" ||
    //             data.lastname == "" ||
    //             data.message == ""
    //         ) {
    //             setMessage("Please fill all fields");
    //             setShow(true);
    //             return false;
    //         } else {
    //             post(route("storeContact"), {
    //                 preserveScroll: true,
    //                 onSuccess: function (response) {
    //                     // setMessage("Email Sent successfully");
    //                     toast.success('Email Sent successfully')
    //                     // Reset the state value
    //                     setShow(true);

    //                     // Reset the form fields
    //                     data.message = "";
    //                     reset();
    //                     setTimeout(function () {
    //                         setShow(false);
    //                     }, 2000);
    //                 },
    //             });
    //         }
    //     } catch (error) {
    //         console.log(error);
    //         // Handle any other errors that occurred during form submission
    //     }
    // };

    // useEffect(() => {
    //     if (show == true) {
    //         setTimeout(function () {
    //             setShow(false);
    //         }, 2000);
    //     }
    // }, [show]);

    const postUrl = `https://mynikahnow.us9.list-manage.com/subscribe/post?u=eb3d0044cb556115fc6b9c235&id=f19b294edf&f_id=000908e1f0`;

    return (
        <div id="contactus" className="relative">
            <div className="py-16 sm:py-20 lg:py-[122px]">
                <div className="container">
                    <div className="grid lg:grid-cols-2 gap-[50px] sm:gap-[100px] xl:gap-[194px] items-center">
                        <div className="max-w-[616px]">
                            <h2 className="text-[#202322] font-gilroy-bold text-[2.7rem] md:text-[3.3rem] xl:text-[4rem] 2xl:text-[4.3rem] leading-[4rem] md:leading-[5rem] lg:leading-[5.5rem] xl:leading-[5.813rem] mb-6 xl:mb-8">
                                Contact Us
                            </h2>
                            <p className="font-gilroy-regular text-xl text-[#909191] mb-10 leading-10">
                                We value your feedback and inquiries! Whether
                                you are an investor, a marketing agent, or a
                                member of the press, we are here for you. Feel
                                free to contact us with any questions, comments,
                                or business inquiries.
                            </p>
                            <div className="flex items-center mb-6">
                                <img
                                    src="/assets/images/landingpage-img/emil-icon.svg"
                                    alt="email icon"
                                    className="mr-3 shrink-0"
                                />
                                <div className="flex flex-col">
                                    <span className="text-[#909191] text-xs block font-gilroy-semibold">
                                        Contact us at
                                    </span>
                                    <strong className="block text-[#202322] text-xl font-gilroy-semibold">
                                        inquiries@mynikahnow.co.uk{" "}
                                    </strong>
                                </div>
                            </div>
                            <div className="flex items-start">
                                <img
                                    src="/assets/images/landingpage-img/location-icon.svg"
                                    alt="email icon"
                                    className="mr-3 shrink-0"
                                />
                                <div className="">
                                    <span className="text-[#909191] text-xs block font-gilroy-semibold">
                                        Address
                                    </span>
                                    <strong className="block text-[#202322] text-xl font-gilroy-semibold">
                                        MyNikahNow Ltd, 71-75 Shelton Street, <br />
                                        Covent Garden,  London, WC2H 9JQ <br /> United Kingdom{" "}
                                    </strong>
                                </div>
                            </div>
                        </div>
                        <div className="bg-white rounded-[20px] shadow-[0px_30px_60px_rgba(0,_0,_0,_0.1)] 2xs:px-5 2xs:pt-[40px] pt-[50px] pb-[31px] px-10">
                            {/* {show == true ? (
                                <div className="alert-success">
                                    <p className="text-black font-bold text-wrap gap-10">{message}</p>
                                </div>
                            ) : (
                                ""
                            )} */}
                            {/* <MailChimp /> */}
                            <MailchimpSubscribe
                                url={postUrl}
                                render={({ subscribe, status, message}) => {
                                    // console.log(status, message)
                                    return <ContactusForm
                                        // posturl={posturl}
                                        status={status}
                                        message={message}
                                        submitForm={async(data) => await subscribe(data)}
                                    />
                                }}
                            />
                            {/* <form className="" onSubmit={onSubmit}>
                                <div className="mb-5">
                                    <TextInput
                                        type="text"
                                        name="firstname"
                                        className={`block w-full h-[60px] px-6 rounded-[10px] border-[#909191] ${
                                            show || errors?.firstname
                                                ? "has-error"
                                                : ""
                                        }`}
                                        isFocused={true}
                                        placeholder="First Name"
                                        value={data?.firstname}
                                        handleChange={onHandleChange}
                                    />
                                    <InputError
                                        message={errors.firstname}
                                        className="mt-2"
                                    />
                                </div>
                                <div className="mb-5">
                                    <TextInput
                                        type="text"
                                        name="lastname"
                                        className={`block w-full h-[60px] px-6 rounded-[10px] border-[#909191] ${
                                            show || errors?.lastname
                                                ? "has-error"
                                                : ""
                                        }`}
                                        // autoComplete="off"
                                        isFocused={true}
                                        placeholder="Last Name"
                                        value={data?.lastname}
                                        handleChange={onHandleChange}
                                    />
                                    <InputError
                                        message={errors.lastname}
                                        className="mt-2"
                                    />
                                </div>
                                <div className="mb-5">
                                    <TextInput
                                        type="text"
                                        name="email"
                                        className={`block w-full h-[60px] px-6 rounded-[10px] border-[#909191] ${
                                            show || errors?.email
                                                ? "has-error"
                                                : ""
                                        }`}
                                        isFocused={true}
                                        placeholder="E-mail"
                                        value={data?.email}
                                        handleChange={onHandleChange}
                                    />
                                    <InputError
                                        message={errors.email}
                                        className="mt-2"
                                    />
                                </div>
                                <div className="mb-5">
                                    <textarea
                                        className={`w-full px-6 py-5 rounded-[10px] h-[165px] resize-none border border-[#909191] focus:border-themecolor focus:ring-0 ${
                                            show || errors?.message
                                                ? "has-error"
                                                : ""
                                        }`}
                                        placeholder="Message"
                                        name="message"
                                        onChange={onHandleChange}
                                        value={data.message}
                                    >
                                    </textarea>
                                    <InputError message={errors.message} className="mt-2"/>
                                </div>
                                <div className="mb-8">
                                    <label className="flex items-center text-[#202322] text-base font-gilroy-semibold">
                                        <Checkbox className="mr-3 w-5 h-5" />
                                        Please read our{" "}
                                        <Link href="/terms-and-conditions" className="text-themecolor ml-1">
                                            Terms & Conditions.
                                        </Link>
                                    </label>
                                </div>
                                <button
                                    type="submit"
                                    className="w-full h-[60px] rounded-[30px] text-white bglinear-gradient text-lg font-medium font-product-sansregular mx-auto"
                                    // onClick={funcHandler}
                                    // onClick={props.closeModal}
                                >
                                    Submit
                                </button>
                            </form> */}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default ContactUs;
