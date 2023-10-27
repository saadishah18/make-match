import { Dialog, Transition } from '@headlessui/react'
import React, { Fragment, useState } from 'react'
import TextInput from './TextInput'
import {useForm} from "@inertiajs/inertia-react";
import {usePage} from "@inertiajs/inertia-react";
import {toast} from "react-toastify";

export default function ChangePasswordModal(props) {
    // const checkPage = usePage().props;
    const { data, setData, post, processing, errors, reset } = useForm({
        password: '',
        password_confirmation: '',
        old_password: '',
    });
    const [error, setError] = useState(false);
    function funcHandler() {
        props.isOpen(true)
        props.closeModal(false)
    }

    const submitHandler = (e) => {
        e.preventDefault();
        if (data.password == '' || data.old_password == '' || data.password_confirmation == '') {
            // toast.error('Fill all fields');
            setError(true);
            return;
        }
        debugger;
        // post(route('register'));
        post(route("updatePassword"),{
            preserveScroll: true,
            onError:function (error) {
                console.log({error})
                console.log({errors})
                // setLoading(false);
            },
            onSuccess:function (response) {
                props.closeModal(true);
                // alert('here');
                reset({
                    password: '',
                    old_password: '',
                    password_confirmation:'',
                });
                props.closeModal;
            }
        });
    };

    const onHandleChange = (event) => {
        setData(event.target.name, event.target.type === 'checkbox' ? event.target.checked : event.target.value);
    };

    return (
        <>
            <Transition appear show={props.isOpen} as={Fragment}>
                <Dialog as="div" className="relative z-10" onClose={props.closeModal}>
                    <Transition.Child
                        as={Fragment}
                        enter="ease-out duration-300"
                        enterFrom="opacity-0"
                        enterTo="opacity-100"
                        leave="ease-in duration-200"
                        leaveFrom="opacity-100"
                        leaveTo="opacity-0"
                    >
                        <div className="fixed inset-0 bg-black bg-opacity-25" />
                    </Transition.Child>

                    <div className="fixed inset-0 overflow-y-auto">
                        <div className="flex min-h-full items-center justify-center p-4 text-center">
                            <Transition.Child
                                as={Fragment}
                                enter="ease-out duration-300"
                                enterFrom="opacity-0 scale-95"
                                enterTo="opacity-100 scale-100"
                                leave="ease-in duration-200"
                                leaveFrom="opacity-100 scale-100"
                                leaveTo="opacity-0 scale-95"
                            >
                                <Dialog.Panel className="w-full max-w-[505px] transform overflow-hidden rounded-2xl bg-white p-10 px-14 text-left align-middle shadow-xl transition-all">
                                    <Dialog.Title
                                        as="h3"
                                        className="text-xl text-center font-product_sansregular mb-6 text-black"
                                    >
                                        Change Password
                                    </Dialog.Title>
                                    <div className="w-full">

                                        {
                                            error ? (
                                                <div className="text-red-500">Fill All the fields</div>
                                            ) : ''
                                        }


                                        <form className="" onSubmit={submitHandler}>
                                            <fieldset>
                                                <div className="mb-5">
                                                    <label className="block text-sm text-black font-product_sansregular mb-2">
                                                        Old Password
                                                    </label>
                                                    <TextInput
                                                        className={`!border !border-[#C0BCBC] !h-[50px] !w-full ${error ? '!border-red-600' : ' !border-[#C0BCBC]'} `}
                                                        type="password"
                                                        placeholder=""
                                                        name="old_password"
                                                        // value={data.email}
                                                        handleChange={onHandleChange}
                                                    />
                                                    {errors.old_password && <div>{errors.old_password}</div>}
                                                </div>
                                                <div className="mb-5">
                                                    <label className="block text-sm text-black font-product_sansregular mb-2">
                                                        New Password
                                                    </label>
                                                    <TextInput
                                                        className={`!border !border-[#C0BCBC] !h-[50px] !w-full ${error ? '!border-red-600' : ' !border-[#C0BCBC]'} `}
                                                        type="password"
                                                        placeholder=""
                                                        name="password"
                                                        // value={data.email}
                                                        handleChange={onHandleChange}
                                                    />
                                                    {errors.password && <div>{errors.password}</div>}
                                                </div>
                                                <div className="mb-5">
                                                    <label className="block text-sm text-black font-product_sansregular mb-2">
                                                        Confirm Password
                                                    </label>
                                                    <TextInput
                                                        className={`!border !border-[#C0BCBC] !h-[50px] !w-xsfull ${error ? '!border-red-600' : ' !border-[#C0BCBC]'} `}
                                                        type="password"
                                                        placeholder=""
                                                        name="password_confirmation"
                                                        // value={data.email}
                                                        handleChange={onHandleChange}
                                                    />
                                                    {errors.password_confirmation && <div>{errors.password_confirmation}</div>}
                                                </div>
                                                <div className="mt-4 flex items-center justify-center">
                                                    <button
                                                        type="submit"
                                                        className="w-full rounded-lg min-h-[56px] text-white bglinear-gradient text-lg font-medium font-product-sansregular mx-auto"
                                                        // onClick={funcHandler}
                                                        // onClick={props.closeModal}
                                                    >
                                                        Update Password
                                                    </button>
                                                </div>
                                            </fieldset>
                                        </form>
                                    </div>
                                </Dialog.Panel>
                            </Transition.Child>
                        </div>
                    </div>
                </Dialog>
            </Transition>
        </>
    )
}
