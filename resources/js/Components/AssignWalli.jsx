import { Dialog, Transition } from '@headlessui/react'
import { Fragment, useState } from 'react'
import Select from 'react-select'

const options = [
    {value: 'Walli1', label: 'Walli1'},
    {value: 'Walli2', label: 'Walli2'},
    {value: 'Walli3', label: 'Walli3'},
    {value: 'Walli4', label: 'Walli4'},
    {value: 'Walli5', label: 'Walli5'},
]

export default function AssignWalliModal(props) {

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
                                <Dialog.Panel className="w-full max-w-[505px] transform rounded-2xl bg-white p-10 px-14 text-left align-middle shadow-xl transition-all">
                                    <Dialog.Title
                                        as="h3"
                                        className="text-xl text-center font-product_sansregular mb-6 text-black"
                                    >
                                        Assign Walli
                                    </Dialog.Title>
                                    <div className="w-full">
                                        <form className="">
                                            <fieldset>
                                                <div className="mb-5">
                                                    <label className="block text-sm text-black font-product_sansregular mb-2">
                                                        Select Walli
                                                    </label>
                                                    <Select
                                                        placeholder="Select Walli"
                                                        options={options}
                                                        className="at-chartselect sm:mb-0 mb-5"
                                                        // onChange={async (e) => {
                                                        //     await setYear(e.value);
                                                        //     handleChartFilter(month, e.value)
                                                        // }}
                                                        name="year_filter"
                                                    />
                                                </div>
                                            </fieldset>
                                        </form>
                                    </div>
                                    <div className="mt-4 flex items-center justify-center">
                                        <button
                                            type="button"
                                            className="w-full rounded-lg min-h-[56px] text-white bglinear-gradient text-lg font-medium font-product-sansregular mx-auto"
                                            // onClick={funcHandler}
                                            onClick={props.closeModal}
                                        >
                                            Assign
                                        </button>
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
