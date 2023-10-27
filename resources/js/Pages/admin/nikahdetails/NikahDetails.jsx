import React, {Fragment, useEffect, useState,useRef} from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import {Head, Link, usePage} from '@inertiajs/inertia-react';
import LoadingCircle from "@/Components/LoadingCircle";
import Certificate from "@/Components/Certificate";
import domtoimage from 'dom-to-image';
import ValidateCertificateModal from "@/Components/ValidateCertificateModal";
import moment from "moment";
import 'moment-timezone'

export default function NikahDetails(props) {
    const [nikah, setNikah] = useState([]);
    const [loader, setLoader] = useState(true);
    let [showCertificate, setShowCertificate] = useState(false);
    let [waliStatus, setWaliStatus] = useState(0);
    const [count, setCount] = useState(0)
    const imageRef = useRef(null);
    const [imageSrc, setImageSrc] = useState(null);
    const {auth} = usePage().props;

    useEffect(() => {
        async function fetchMyAPI() {
            let response =  props.nikah_detail
            response = response.data
            await setNikah(response)
        }
        fetchMyAPI()
        setLoader(false);
    }, [props])

    function closeCertificateModal() {
        setShowCertificate(false)
    }
    function openCertificateModal() {
        setShowCertificate(true)
    }

/*
    useEffect(() => {
        if(imageRef.current){
            domtoimage.toPng(imageRef.current)
                .then(function (dataUrl) {
                    setImageSrc(dataUrl);
                    var link = document.createElement('a');
                    link.download = nikah.groom+'-nikah-certificate.jpeg';
                    link.href = dataUrl;
                    console.log(dataUrl);
                })
                .catch(function (error) {
                    console.error('oops, something went wrong!', error);
                });
        }
    }, [imageRef.current]);
*/

    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Nikah Details</h2>}
        >
            <Head title="Nikah Detail"/>
            {
                loader ? <LoadingCircle loading={loader} /> : (
                    <>
                        <div className="mb-6 block md:flex items-center w-full">
                            <Link className="flex items-center justify-center text-black text-sm font-productsans-bold gap-5 mr-5" as="button"
                                  href={route('nikahmanagement')}>
                                <em className="flex justify-center items-center w-[44px] h-[44px] bglinear-gradient rounded-xl">
                                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M10.995 4.99997H3.40501L6.70501 1.71268C6.89332 1.52453 6.9991 1.26935 6.9991 1.00326C6.9991 0.737183 6.89332 0.481998 6.70501 0.29385C6.51671 0.105701 6.26132 0 5.99501 0C5.72871 0 5.47332 0.105701 5.28501 0.29385L0.285014 5.28973C0.193973 5.38476 0.122608 5.49681 0.0750135 5.61946C-0.0250045 5.86272 -0.0250045 6.13557 0.0750135 6.37883C0.122608 6.50149 0.193973 6.61354 0.285014 6.70856L5.28501 11.7044C5.37798 11.7981 5.48858 11.8724 5.61044 11.9232C5.7323 11.9739 5.863 12 5.99501 12C6.12703 12 6.25773 11.9739 6.37959 11.9232C6.50145 11.8724 6.61205 11.7981 6.70501 11.7044C6.79874 11.6116 6.87314 11.501 6.9239 11.3793C6.97467 11.2575 7.00081 11.1269 7.00081 10.995C7.00081 10.8631 6.97467 10.7325 6.9239 10.6108C6.87314 10.489 6.79874 10.3785 6.70501 10.2856L3.40501 6.99832H10.995C11.2602 6.99832 11.5146 6.89305 11.7021 6.70567C11.8897 6.51829 11.995 6.26415 11.995 5.99915C11.995 5.73415 11.8897 5.48001 11.7021 5.29262C11.5146 5.10524 11.2602 4.99997 10.995 4.99997Z"
                                            fill="#fff"/>
                                    </svg>
                                </em>
                            </Link>
                            <h3 className="text-black text-[1.75rem] leading-7 font-product_sans_mediumregular">Nikah Details</h3>
                        </div>
                        <div className="w-full bg-white p-10 rounded-2xl">
                            <h3 className="text-xl mb-5 font-medium">Basic Information</h3>
                            <ul className="flex flex-wrap items-center mb-5 border-b border-b-black">
                                <li className="w-1/2 mb-3">
                                    <strong className="block font-medium">Groom</strong>
                                    <span className="block">{nikah?.groom}</span>
                                </li>
                                <li className="w-1/2 mb-3">
                                    <strong className="block font-medium">Bride</strong>
                                    <span className="block">{nikah?.bride}</span>
                                </li>
                                <li className="w-1/2 mb-3">
                                    <strong className="block font-medium">Time</strong>
                                    <span className="block">{nikah?.start_time}</span>
                                </li>
                                <li className="w-1/2 mb-3">
                                    <strong className="block font-medium">Date</strong>
                                    <span className="block">{nikah?.start_date}</span>
                                </li>
                                <li className="w-1/2 mb-3">
                                    <strong className="block font-medium">Category</strong>
                                    <span className="block">{nikah?.nikah_type}</span>
                                </li>
                                <li className="w-1/2 mb-3">
                                    <strong className="block font-medium">Status</strong>
                                    <span className="block">{nikah?.history?.current_status}</span>
                                </li>
                                <li className="w-1/2 mb-3">
                                    <strong className="block font-medium">Zoom Link</strong>
                                    <span className="block">{nikah?.link}</span>
                                </li>
                            </ul>
                            <h3 className="text-xl mb-5 font-medium">Service Information</h3>
                            <ul className="flex flex-wrap items-center mb-5 border-b border-b-black">
                                {
                                    nikah.services ? nikah.services.length ? nikah.services.map((item, i) => (
                                        <li className="w-1/2 mb-3">
                                            <strong className="block font-medium">{item.name}</strong>
                                            <span className="block">{item.price ? item.price+' GBP' : 'N/A'} </span>
                                        </li>
                                    )) : 'Loading..' : 'No Services Included'
                                }
                            </ul>
                            <div className="flex flex-wrap items-center mb-5 border-b border-b-black">
                                <h3 className="text-xl mb-5 font-medium">Witnesses</h3>
                                <div className="w-full bg-white rounded-2xl">
                                    <ul>
                                        {nikah.assingned_witness.map((item, i) => (
                                            <li className="w-1/2 mb-3">
                                                {item.email}
                                            </li>
                                        ))}
                                    </ul>
                                </div>
                            </div>
                            <div className="flex flex-wrap items-center mb-5 border-b border-b-black">
                                <h3 className="text-xl mb-5 font-medium">{nikah.wali != null ? 'Wali' : 'Wakeel'}</h3>
                                <div className="w-full bg-white rounded-2xl">
                                    <ul>
                                        <li className="w-1/2 mb-3">
                                            {nikah.wali != null ? nikah.wali.email : nikah.assigned_imam}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <h3 className="text-xl mb-5 font-medium">Certificates</h3>

                            <ValidateCertificateModal isOpen={showCertificate} closeModal={closeCertificateModal} nikah={nikah} showbtn='false'/>
                            <figure className="w-full">
                                <img className="block" src={nikah.certificate_url} onClick={openCertificateModal} />
                            </figure>
                        </div>
                    </>
                )
            }
        </AuthenticatedLayout>
    );
}
