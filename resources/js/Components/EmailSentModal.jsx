import {Dialog, Transition} from '@headlessui/react'
import React, {Fragment, useState} from 'react'
import {useForm, Head, Link,  usePage} from "@inertiajs/inertia-react";
import PrimaryButton from "@/Components/PrimaryButton";

export default function EmailSentModal(props) {
    let [isOpen, setIsOpen] = useState(false)
    const [loading, setLoading] = useState(false);
    const {data, setData, post, processing} = useForm({
        email: '',
    });


    const [email,setEmail]=useState('')


    function closeModal() {
        setIsOpen(false)
    }

    function openModal() {
        setIsOpen(true)
    }


    const submit = (e) => {
        setEmail(props.email)
        setLoading(true)
        post(route('password.email'), {
            preserveScroll: true,
            onError: function () {
                setLoading(false);
            },
            onSuccess: function () {
                setLoading(false);
                openModal()
            }
        });
    }

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
                        <div className="fixed inset-0 bg-black bg-opacity-25"/>
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
                                <Dialog.Panel
                                    className="w-full max-w-[610px] min-h-[440px] flex items-center justify-center transform overflow-hidden rounded-2xl bg-white p-6 px-14 text-left align-middle shadow-xl transition-all">
                                    <div className="w-full flex items-center justify-center flex-col">
                                        <img
                                            className="w-[94px]"
                                            src="/assets/images/svg/email-envelope.svg"
                                            alt="Envelope"
                                        />
                                        <h4 className="text-black font-bold font-product_sansregular text-2xl mt-7 mb-2">
                                            {/*Temporary password sent!*/}
                                            Password reset link sent!
                                        </h4>
                                        <p className="block text-lightblack text-center text-base font-product_sansregular">
                                            {/*Please check your inbox and enter the temporary*/}
                                            Please check your inbox and click on link
                                            <span className="block">
                                                {' '}
                                                sent on your email.
                                            </span>
                                        </p>
                                        <div className="flex items-center justify-center mt-12">
                                          <span className="text-base text-black font-product-sansregular flex items-center justify-between gap-2">
                                            Din’t Receive the link?{' '}
                                              <button
                                                  type="button"
                                                  className="text-themecolor font-product_sans_mediumregular tracking-wider"
                                                  onClick={submit}
                                              >
                                              {' '}
                                                   Resend
                                            </button>
                                              {loading && <div className="lds-dual-ring-two"></div>}
                                          </span>
                                        </div>
                                        {/*<Link href={route('newpassword')}>
                                            <PrimaryButton type="button" className="w-full">
                                                New Password
                                            </PrimaryButton>
                                        </Link>*/}
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
