import Checkbox from "@/Components/Checkbox";
import PrimaryButton from "@/Components/PrimaryButton";
import TextInput from "@/Components/TextInput";
import { Link, useForm } from "@inertiajs/inertia-react";
import InputError from "@/Components/InputError";
import React, { useEffect, useState } from "react";
import { Inertia } from "@inertiajs/inertia";
import {toast} from "react-toastify";

const ContactusForm = ({ submitForm}) => {
    const { data, setData, post, processing, errors, reset } = useForm({
        email: "",
        firstname: "",
        lastname: "",
        message: "",
    });
    const [show, setShow] = useState(false);
    const [message, setMessage] = useState(false);

    const onHandleChange = (event) => {
        setData(
            event.target.name,
            event.target.type === "checkbox"
                ? event.target.checked
                : event.target.value
        );
    };

    const onSubmit = async (e) => {
        try {
            e.preventDefault();
            if (
                data.email == "" ||
                data.firstname == "" ||
                data.lastname == "" ||
                data.message == ""
            ) {
                // setMessage("Please fill all fields");
                toast.error('Please fill all fields');
                setShow(true);
                return false;
            }else {
                // submitForm({EMAIL: data.email, FNAME: data.firstname, LNAME: data.lastname,PHONE: "03001231231"})
                // post(route("storeContact"), {
                post(route("storeContact"), {
                    preserveScroll: true,
                    onSuccess: function (response) {
                        setMessage("Email Sent successfully");
                        toast.success('Email Sent successfully')
                        setShow(true);
                        data.message = "";
                        reset();
                        setTimeout(function () {
                            setShow(false);
                        }, 2000);
                    },
                });
            }
        } catch (error) {
            console.log(error);
            // Handle any other errors that occurred during form submission
        }
    };

    useEffect(() => {
        if (show == true) {
            setTimeout(function () {
                setShow(false);
            }, 2000);
        }
    }, [show]);

  return (
    <>
        {/*{show == true ? (*/}
        {/*    <div className="alert-success">*/}
        {/*        <p className="text-black font-bold text-wrap gap-10">{message}</p>*/}
        {/*    </div>*/}
        {/*) : (*/}
        {/*    ""*/}
        {/*)}*/}
        <form className="" onSubmit={onSubmit}>
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
                    <Checkbox className="mr-3 w-5 h-5"  handleChange={onHandleChange}/>
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
        </form>
    </>
  )
}

export default ContactusForm
