import {Dialog, Transition} from '@headlessui/react'
import React, {Fragment, useState} from 'react'
import TextInput from './TextInput'
import moment from "moment";

export default function TalaqCertificateModal(props) {
    console.log({props});

    function funcHandler() {
        props.isOpen(true)
        props.closeModal(false)
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
                                <Dialog.Panel className="w-full max-w-[940px] transform rounded-2xl bg-white relative p-14 text-left align-middle shadow-xl transition-all bg-[url('/assets/images/border-img.svg')] bg-no-repeat bg-cover">
                                    <div className="w-full">
                                        <div className="w-full bg-white px-20 pb-[170px] relative bg-cover bg-no-repeat overflow-hidden  bg-[url('/assets/images/certificate-bg-img.svg')]">
                                            <div className="p-8 flex justify-center flex-col items-center ">
                                                <figure className="w-28 h-28 relative">
                                                    <img
                                                        src="/assets/images/certificate-logo.svg"
                                                        className="w-full"
                                                        alt="logo"
                                                    />
                                                </figure>
                                                <h2 className="text-3xl mt-5 text-black font-semibold border-b border-[#DBDBDB] pb-5 px-5">
                                                    Talaq Certificate
                                                </h2>
                                            </div>
                                            <div className="flex justify-center items-center flex-col">
                                                <h3 className="mt-5 text-[#E16AA4] font-semibold">
                                                    {props.talaq.requester} & {props.talaq.bride}
                                                </h3>
                                            </div>
                                            <p className="text-[#909191] font-normal text-center mt-6">
                                                Talaq was held
                                                on {moment(props.talaq.talaq_date).format('dddd, MMMM Do YYYY')}
                                            </p>
                                            <div className="mt-16 flex justify-evenly flex-wrap gap-10">
                                                <div className="gap-10 flex">
                                                    <div className="text-center">
                                                          <span className="block pb-3">
                                                                {props.talaq?.wali != null ? props.talaq?.wali?.email : props.talaq?.assigned_imam}
                                                          </span>
                                                        <h6 className="text-black border-t border-[#DBDBDB] px-6 py-2">
                                                            Wali / Wakeel Name
                                                        </h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
