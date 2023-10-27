import {Dialog, Transition} from '@headlessui/react'
import {Fragment, useEffect, useState} from 'react'
import {Head, useForm, usePage, Link} from '@inertiajs/inertia-react';
import {toast} from "react-toastify";

export default function UploadCerificateModal(props) {
    const {data, setData, errors, post, progress,setDefaults} = useForm({
        image: null,nikah_id:'',
    });

    // const [nikah, setNikah] = useState({});



    function funcHandler() {
        // props.handler()
        props.closeModal()
    }

    const  handleSubmit = async (e) => {
        e.preventDefault();
        data.nikah_id = props.nikahObject.nikah_id;
        await setData({nikah_id:  data.nikah_id})
        post(route("imam.uploadCertificates"),{
            preserveScroll: true,
            onError:function (error) {
                // setLoading(false);
                // alert('erer');
                props.closeModal()
            },
            onSuccess:function (response) {
                // alert('jere');
                // alert('succ');
                props.closeModal()
            }
        });
        setData("image", null)
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
                                    className="w-full max-w-[550px] flex items-center justify-center transform overflow-hidden rounded-2xl bg-white p-10 px-14 text-left align-middle shadow-xl transition-all">
                                    <form name="createForm" onSubmit={handleSubmit}>
                                        {/*<input type="hidden" name="nikah_detail" value= "" />*/}
                                        <div className="w-full flex items-center justify-center flex-col">
                                            <div className="w-full float-left min-h-[250px] flex items-center justify-center flex-col">
                                                <input type="file" name="image" onChange={e => setData('image', e.target.files[0])} />
                                                <span className="text-red-600">
                                            {errors.image}
                                        </span>
                                                <>
                                                    {/*  <input className="hidden" type="file" name="image" id="at-uploadfile"/>
                                                <label className="flex justify-center items-center flex-col cursor-pointer" htmlFor="at-uploadfile">
                                                    <img src="/assets/images/svg/upload-icon.svg" alt="Upload Icon"/>
                                                    <span className="text-gray1 text-lg font-product_sansbold block mt-5"
                                                          onChange={(e) => setData("image", e.target.images[0])}>Upload Certificate file</span>
                                                </label>*/}
                                                </>

                                            </div>

                                            <div className="w-full float-left mt-11 flex items-center justify-center gap-3">
                                                <button type="button"
                                                    className="min-h-[60px] text-lg font-product_sansregular font-bold tracking-wider border-[2px] border-gray1 rounded-[10px] min-w-[168px] bg-white"
                                                    onClick={props.closeModal} > Cancel </button>
                                                <button type="submit" className="min-h-[60px] text-lg font-product_sansregular font-bold tracking-widest rounded-[10px] min-w-[168px] px-5 text-white bglinear-gradient"> Save </button>
                                            </div>
                                        </div>
                                    </form>
                                </Dialog.Panel>
                            </Transition.Child>
                        </div>
                    </div>
                </Dialog>
            </Transition>
        </>
    )
}
