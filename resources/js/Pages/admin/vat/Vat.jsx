import React, {useEffect} from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import TextInput from "@/Components/TextInput";
// import InputError from "@/Components/InputError";
import {Head, Link, useForm, usePage} from '@inertiajs/inertia-react';
import PrimaryButton from "@/Components/PrimaryButton";
import {toast} from "react-toastify";




export default function Vat(props) {
    const {flash} = usePage().props;
    const { data, setData, get, processing, errors, reset } = useForm({
         vat: props.vat,
    });
    const submitHandler = (e) => {
        e.preventDefault();
        // alert('this');
        get(route("vat"),{
            preserveScroll: true,
            onError:function (error) {
               toast.error(error)
            },
            onSuccess:function (response) {
                console.log(response.props.success);
                if (response.props.success) {
                    toast.success(response.props.success);
                }
                if (response.props.error) {
                    toast.error(response.props.error);
                }
            }
        });
    };

    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Vat</h2>}
        >
            <Head title="Vat %" />
            <div className="at-pagehead mb-6 block md:flex items-center justify-between">
                <h3 className="text-black text-[1.75rem] leading-7 font-product_sans_mediumregular">VAT</h3>
            </div>
            <form className="at-formaddemployee w-full" onSubmit={submitHandler}>
                <fieldset className="w-full">

                    <input
                        type="text"
                        className="block w-1/3"
                        name="vat"
                        value={data.vat}
                        onChange={(e) =>
                            setData("vat", e.target.value)
                        }
                        placeholder="Vat 25 %"
                    />
                    {/*<InputError message={} className="mt-2" />*/}
                    <strong className="block mt-2 text-black">Enter vat percentage %</strong>
                    <PrimaryButton type="submit" className="mt-4">Update Vat</PrimaryButton>

                </fieldset>
            </form>
        </AuthenticatedLayout>
    );
}
