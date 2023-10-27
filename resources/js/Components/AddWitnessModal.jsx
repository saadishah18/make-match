import { Dialog, Transition } from '@headlessui/react'
import { Fragment, useState } from 'react'
import TextInput from './TextInput'
import {useForm} from "@inertiajs/inertia-react";
import {usePage} from "@inertiajs/inertia-react";

export default function AddWitnessModal(props) {
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
                // alert('here');
                reset({
                    first_name: '',
                    last_name: '',
                    email:'',
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
                                        Add Witness
                                    </Dialog.Title>
                                    <div className="w-full">
                                        <form className="" onSubmit={submitHandler}>
                                            <fieldset>
                                                <div className="mb-5">
                                                    <label className="block text-sm text-black font-product_sansregular mb-2">
                                                        First Name
                                                    </label>
                                                    <TextInput
                                                        className="!border !border-[#C0BCBC] !h-[50px]"
                                                        type="text"
                                                        placeholder=""
                                                        name="first_name"
                                                        handleChange={onHandleChange}
                                                        // value={data.first_name}
                                                    />
                                                    {errors.first_name && <div>{errors.first_name}</div>}
                                                </div>
                                                <div className="mb-5">
                                                    <label className="block text-sm text-black font-product_sansregular mb-2">
                                                        Last Name
                                                    </label>
                                                    <TextInput
                                                        className="!border !border-[#C0BCBC] !h-[50px]"
                                                        type="text"
                                                        placeholder=""
                                                        name="last_name"
                                                        // value={data.last_name}
                                                        handleChange={onHandleChange}
                                                    />
                                                    {errors.last_name && <div>{errors.last_name}</div>}
                                                </div>
                                                <div className="mb-5">
                                                    <label className="block text-sm text-black font-product_sansregular mb-2">
                                                        Email
                                                    </label>
                                                    <TextInput
                                                        className="!border !border-[#C0BCBC] !h-[50px] !w-full"
                                                        type="email"
                                                        placeholder=""
                                                        name="email"
                                                        // value={data.email}
                                                        handleChange={onHandleChange}
                                                    />
                                                    {errors.email && <div>{errors.email}</div>}
                                                </div>
                                                <div className="mt-4 flex items-center justify-center">
                                                    <button
                                                        type="submit"
                                                        className="w-full rounded-lg min-h-[56px] text-white bglinear-gradient text-lg font-medium font-product-sansregular mx-auto"
                                                        // onClick={funcHandler}
                                                        // onClick={props.closeModal}
                                                    >
                                                        Create Witness
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
