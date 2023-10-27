import { Dialog, Transition } from '@headlessui/react'
import React, { Fragment, useState } from 'react'
import TextInput from './TextInput'
import {useForm} from "@inertiajs/inertia-react";
import {usePage} from "@inertiajs/inertia-react";
import Select from "react-select";

export default function NewChat(props) {
    // const checkPage = usePage().props;
    const { data, setData, post, processing, errors, reset } = useForm({
        first_name: '',
        last_name: '',
        email: '',
    });
    function funcHandler() {
        props.isOpen(true)
        props.closeModal(false)
    }

    const submitHandler = (e) => {
        // alert('here');
        e.preventDefault();
        // post(route('register'));
        post(route("witness.store"),{
            preserveScroll: true,
            onError:function (error) {
                console.log({error})
                console.log({errors})
                // setLoading(false);
            },
            onSuccess:function (response) {
                props.closeModal(true);
                alert('here');
                reset({
                    first_name: '',
                    last_name: '',
                    email:'',
                });
                props.closeModal;

            }
        });
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
                                <Dialog.Panel className="w-full max-w-[505px] h-96 transform overflow-hidden rounded-2xl bg-white p-10 px-14 text-left align-middle shadow-xl transition-all">
                                    <Dialog.Title
                                        as="h3"
                                        className="text-xl text-center font-product_sansregular mb-6 text-black"
                                    >
                                        New Chat
                                    </Dialog.Title>
                                    <div className="w-full h-96">
                                        <form className="" onSubmit={submitHandler}>
                                            <fieldset>
                                                <Select placeholder="Choose user to start chat"
                                                        options={props.chatUsers} name="shift_time"
                                                        className=" basic-multi-select" classNamePrefix="select"
                                                        onChange={(e) => props.onChangeUser(e)}
                                                />
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
