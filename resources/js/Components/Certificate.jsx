import { Dialog, Transition } from '@headlessui/react'
import React, { Fragment, useState } from 'react'
import Select from "react-select";


const Certificate = (props) => {
    console.log(props.nikah_detail)
    const nikah = props.nikah_detail;
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
                                <Dialog.Panel className="w-full max-w-[905px] transform rounded-2xl bg-white p-10 px-14 text-left align-middle shadow-xl transition-all">
                                    <div className="w-full">
                                        <div className="   w-full bg-white px-40 relative  h-screen bg-cover overflow-hidden  bg-[url('assets/images/certificate-logo.png')]">
                                            <div className="p-20 flex justify-center flex-col items-center ">
                                                <figure className="w-28 h-28 relative">
                                                    <img src="/assets/images/certificate-logo.png" alt=""/>
                                                </figure>
                                                <h2 className="text-3xl mt-5 text-black   font-semibold ">
                                                    Nikah Certificate
                                                </h2>
                                            </div>
                                            <div className="border-b border-[#DBDBDB] -mt-8 "></div>
                                            <div className="flex justify-center items-center flex-col ">
                                              {/*  <p className="text-[#909191] font-normal text-center  md:w-[30rem] mt-6">
                                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis id
                                                    orci venenatis.
                                                </p>*/}
                                                <h3 className="mt-10 text-[#E16AA4] font-semibold">
                                                    {nikah.groom} & {nikah.bride}
                                                </h3>
                                            </div>
                                            <p className="text-[#909191] font-normal text-center mt-6">
                                               Nikah was held on {nikah.start_date} at {nikah.start_time}.
                                            </p>
                                            <div className="mt-16 flex justify-evenly flex-wrap ">

                                                <h6 className="text-black border-t border-[#DBDBDB] px-6 py-2">
                                                    {nikah.assingned_witness[0].email}
                                                    Witness Name
                                                </h6>

                                                <h6 className="text-black border-t border-[#DBDBDB] px-6 py-2">
                                                    {nikah.assingned_witness[0].email}
                                                    Witness Name
                                                </h6>
                                                <h6 className="text-black border-t border-[#DBDBDB] px-6 py-2">
                                                    {nikah.wali != '' ? nikah.wali.email :  nikah.assigned_imam}
                                                    Wali / Wakeel Name
                                                </h6>
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

    );
};

export default Certificate;
