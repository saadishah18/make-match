import { Dialog, Transition } from "@headlessui/react";
import React, { Fragment, useEffect, useRef, useState } from "react";
import TextInput from "./TextInput";
import domtoimage from "dom-to-image";
import axios from "axios";
import { toast } from "react-toastify";
import { router } from "@inertiajs/react";
import { Inertia } from "@inertiajs/inertia";
import moment from "moment";

export default function ValidateCertificateModal(props) {
    const imageRef = useRef(null);
    const [imageSrc, setImageSrc] = useState(null);
    const [loader, setLoader] = useState(true);
    const nikah = props.nikah;

    // console.log(nikah);

    function funcHandler() {
        props.isOpen(true);
        props.closeModal(false);
    }
    const handleValidate = async (nikahdetail) => {
        // console.log(nikahdetail)
        if (nikahdetail.assingned_witness == "") {
            toast.info(
                "Witnesses not assigned to this nikah. Please Contact Admin!"
            );
            return false;
        }
        if (nikahdetail?.wali?.email == null && nikahdetail?.wali?.assigned_imam == 'N/A') {
            toast.info(
                "Wali / Wakeel not assigned,  Please Contact Admin!"
            );
            return false;
        }
        setLoader(true);
        if (imageRef.current) {
            domtoimage.toPng(imageRef.current).then(function (dataUrl) {
                    setImageSrc(dataUrl);
                    var link = document.createElement("a");
                    link.download = nikahdetail.groom + "-nikah-certificate.jpeg";
                    link.href = dataUrl;
                    axios.post(route("imam.validateNikah"), {
                            image_url: dataUrl,
                            nikah_detail: nikahdetail,
                        })
                        .then(function (response) {
                            let { data } = response;
                            // router.reload({ only: ['nikahs'] }) // Uses the current URL
                            setLoader(false);
                            props.closeModal(false);
                            toast.success(data.message);
                            Inertia.reload();
                        })
                        .catch(function (error) {
                            toast.error( error.response.data.message);
                            setLoader(false);
                        });
                })
                .catch(function (error) {
                    // console.error('oops, something went wrong!', error);
                    toast.error("oops, something went wrong!", error);
                });
        }
    };

    /*  useEffect(() => {

    }, [imageRef.current]);*/

    return (
        <>
            <Transition appear show={props.isOpen} as={Fragment}>
                <Dialog
                    as="div"
                    className="relative z-10"
                    onClose={props.closeModal}
                >
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
                                <Dialog.Panel className="w-full max-w-[940px] transform rounded-2xl bg-white relative p-14 text-left align-middle shadow-xl transition-all bg-[url('/assets/images/border-img.svg')] bg-no-repeat bg-cover">
                                    <div className="w-full" ref={imageRef}>
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
                                                    Nikah Certificate
                                                </h2>
                                            </div>
                                            <div className="flex justify-center items-center flex-col">
                                                {/*  <p className="text-[#909191] font-normal text-center  md:w-[30rem] mt-6">
                                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis id
                                                    orci venenatis.
                                                </p>*/}
                                                <h3 className="mt-5 text-[#E16AA4] font-semibold">
                                                    {nikah?.groom} &{" "}
                                                    {nikah?.bride}
                                                </h3>
                                            </div>
                                            <p className="text-[#909191] font-normal text-center mt-6">
                                                Nikah was held on{" "} {nikah?.start_date} at{" "} {moment(nikah?.start_time_simple).utc().format('hh:mm A')} UTC Time.
                                            </p>
                                            <div className="mt-16 flex justify-evenly flex-wrap gap-10">
                                                <div className="gap-10 flex">
                                                    {nikah.assingned_witness
                                                        ? nikah.assingned_witness.map(
                                                              (item, i) => (
                                                                  <>
                                                                      <div className="text-center">
                                                                          <span className="block pb-3">
                                                                              {nikah.assingned_witness
                                                                                  ? item.full_name != null ? item.full_name  : item.email
                                                                                  : ""}
                                                                          </span>
                                                                          <h6 className="text-black border-t border-[#DBDBDB] px-6 py-2">
                                                                              Witness
                                                                              Name
                                                                          </h6>
                                                                      </div>
                                                                  </>
                                                              )
                                                          )
                                                        : ""}
                                                </div>

                                                <div className="text-center">
                                                    <span className="block pb-3">
                                                        {nikah?.wali != null
                                                            ? nikah?.wali?.full_name != null ? nikah?.wali?.full_name : nikah?.wali?.email
                                                            : nikah?.assigned_imam}
                                                    </span>
                                                    <h6 className="text-black border-t border-[#DBDBDB] px-6 py-2">
                                                        Wali / Wakeel Name
                                                    </h6>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    {props.showbtn == "true" ? (
                                        <div className="mt-4 flex items-center justify-center">
                                            <button
                                                type="button"
                                                className="w-full rounded-lg min-h-[56px] text-white bglinear-gradient text-lg font-medium font-product-sansregular mx-auto"
                                                // onClick={funcHandler}
                                                // onClick={props.closeModal}
                                                onClick={(e) =>
                                                    handleValidate(nikah)
                                                }
                                            >
                                                Validate
                                            </button>
                                        </div>
                                    ) : (
                                        ""
                                    )}
                                </Dialog.Panel>
                            </Transition.Child>
                        </div>
                    </div>
                </Dialog>
            </Transition>
        </>
    );
}
