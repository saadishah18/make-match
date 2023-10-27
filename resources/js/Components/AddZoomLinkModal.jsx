import { Dialog, Transition } from '@headlessui/react'
import { Fragment, useState } from 'react'
import TextInput from './TextInput'
import {toast} from "react-toastify";
import {Inertia} from "@inertiajs/inertia";
import {value} from "lodash/seq";

export default function AddZoomLinkModal(props) {
    const nikah = props.nikah;
    const [zoomlink, setLink] = useState('');

    function funcHandler() {
        props.isOpen(true)
        props.closeModal(false)
    }


    const storeZoomRecording = (nikah_id) => {
        if(zoomlink == ''){
            toast.info('Please insert zoom recorded link')
            return false;
        }
        // console.log(nikah_id);
        // console.log(zoomlink);
        axios.post(route('imam.storeRecordedLink'), {
            nikah_id: nikah_id,
            recorded_link: zoomlink,
        }).then(function (response) {
            if(response.data.status == 200){
                // setLoader(false);
                props.closeModal(false);
                // alert(response.data.message);
                toast.success(response.data.message);
                Inertia.reload()
            }else{
                props.closeModal(false);
                toast.error(response.data.message)
            }

        })
        .catch(function (error) {
            console.log({error});
            let {response} = error;
            props.closeModal(false);
            setLink('');
            toast.error(response.data.message)
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
                                        Add Zoom Link
                                    </Dialog.Title>
                                    <div className="w-full">
                                        <form className="#" onSubmit={e => e.preventDefault()}>
                                            <fieldset>
                                                <div className="mb-5">
                                                    <label className="block text-sm text-black font-product_sansregular mb-2">
                                                        Zoom Link
                                                    </label>
                                                    <TextInput
                                                        className="!border !border-[#C0BCBC] !h-[50px]"
                                                        type="text"
                                                        placeholder=""
                                                        handleChange={(e) => setLink(e.target.value)}
                                                    />
                                                </div>
                                            </fieldset>
                                        </form>
                                    </div>
                                    <div className="mt-4 flex items-center justify-center">
                                        <button
                                            type="button"
                                            className="w-full rounded-lg min-h-[56px] text-white bglinear-gradient text-lg font-medium font-product-sansregular mx-auto"
                                            onClick={() => storeZoomRecording(nikah.nikah_id)}
                                            // onClick={props.closeModal}
                                        >
                                            Save
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
