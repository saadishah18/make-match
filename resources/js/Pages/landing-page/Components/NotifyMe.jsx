import Checkbox from "@/Components/Checkbox";
import PrimaryButton from "@/Components/PrimaryButton";
import TextInput from "@/Components/TextInput";
import { Link, useForm } from "@inertiajs/inertia-react";
import InputError from "@/Components/InputError";
import React, { useEffect, useState } from "react";
import { Inertia } from "@inertiajs/inertia";
import {toast} from "react-toastify";

const NotifyMe = ({ submitNotifyMeForm}) => {

    const [show, setShow] = useState(false);
    const [message, setMessage] = useState(false);
    const [value, setvalue] = useState('');





    const notifyMe = async () => {
        console.log(value == "", value=='', value==null);

        if (value === '') {
            setMessage("Please enter email");
            setShow(true);;
            // alert('if if');
            return false;
        }
        submitNotifyMeForm({EMAIL:value})
        axios.post(route('notifyMe'), {
        email: value,
        }).then(function (response) {
            console.log(response);
            if(response.status == 200){
                setMessage('Email sent successfully');
                setShow(true);
                setTimeout(function (){
                    setShow(false);
                    setvalue('');
                },3000);
            }else{
                setShow(false);
            }

        });
    }

    return (
        <>
            <form className={`flex gap-5 flex-col lg:flex-row`}>
                {
                    show ? (
                        <p className="text-white font-bold text-wrap"> {message}</p>
                    ) : ''
                }
                <div className="w-full">
                    <TextInput
                        type="text"
                        name="email"
                        className={`block w-full h-[60px] px-6 rounded-[10px] border-[#909191] `}
                        isFocused={true}
                        placeholder="E-mail"
                        value={value}
                        handleChange={(e) => setvalue(e.target.value)}
                        // value={data?.email}
                        // handleChange={onHandleChange}
                    />
                </div>
                <button
                    type="button"
                    className="w-full lg:w-auto min-w-[150px] h-[60px] rounded-[30px] border border-white text-white bglinear-gradient text-lg font-medium font-product-sansregular mx-auto"
                    // onClick={funcHandler}
                    onClick={notifyMe}
                >
                    Notify Me
                </button>
            </form>
        </>
    )
}

export default NotifyMe
