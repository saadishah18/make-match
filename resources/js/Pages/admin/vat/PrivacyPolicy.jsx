import React, {useEffect} from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import TextInput from "@/Components/TextInput";
// import InputError from "@/Components/InputError";
import {Head, Link, useForm, usePage} from '@inertiajs/inertia-react';
import PrimaryButton from "@/Components/PrimaryButton";
import {toast} from "react-toastify";
import InputError from "@/Components/InputError";
import TextEditor from "@/Components/TextEditor";
export default function PrivacyPolicy(props) {
    const {flash} = usePage().props;
    const { data, setData, post, get, processing, errors, reset } = useForm({
        privacy_policy: props.privacy_policy, terms_and_conditions:props.terms_and_conditions
    });
    const submitHandler = (e) => {
        e.preventDefault();
        // alert('this');
        post(route("storePrivacyPolicy"),{
            preserveScroll: true,
            onError:function (error) {
                if(error?.privacy_policy){
                    toast.error(error?.privacy_policy)
                }
                if(error?.terms_and_conditions){
                    toast.error(error?.terms_and_conditions)
                }
            },
            onSuccess:function (response) {
                console.log(response);
                if (response.props.success) {
                    toast.success(response.props.success);
                }
                if (response.props.error) {
                    toast.error(response.props.error);
                }
            }
        });
    };

    const handleContentChange = (newContent) => {
        setData("terms_and_conditions", newContent)
    };
    const handlePrivacyChange = (newContent) => {
        setData("privacy_policy", newContent)
    };
    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Privacy Policy / Terms & Conditions</h2>}
        >
            <Head title="Privacy & terms" />
            <div className="at-pagehead mb-6 block md:flex items-center justify-between">
                <h3 className="text-black text-[1.75rem] leading-7 font-product_sans_mediumregular">Privacy Policy / Terms & Conditions</h3>
            </div>
            <form className="at-formaddemployee w-full" onSubmit={submitHandler}>
                <fieldset className="w-full">
                    <label>Privacy Policy</label>
                    {/*<textarea*/}
                    {/*    className={`w-full px-6 py-5 rounded-[10px] h-[165px] resize-none border border-[#909191] focus:border-themecolor focus:ring-0 ${errors?.message*/}
                    {/*            ? "has-error"*/}
                    {/*            : ""*/}
                    {/*    }`}*/}
                    {/*    placeholder="Privacy Policy"*/}
                    {/*    name="privacy_policy"*/}
                    {/*    onChange={(e) => setData("privacy_policy", e.target.value)}*/}
                    {/*>*/}
                    {/*   {data.privacy_policy}*/}
                    {/*</textarea>*/}
                    <TextEditor previousValue={data.privacy_policy} onChange={handlePrivacyChange} />

                    <InputError
                        message={errors?.privacy_policy}
                        className="mt-2"
                    />
                </fieldset>

                <fieldset className="w-full">
                    <label>Terms & Conditions</label>
                   {/* <textarea
                        className={`w-full px-6 py-5 rounded-[10px] h-[165px] resize-none border border-[#909191] focus:border-themecolor focus:ring-0 ${errors?.message
                                ? "has-error"
                                : ""
                        }`}
                        placeholder="Terms And Conditions"
                        name="message"
                        onChange={(e) => setData("terms_and_conditions", e.target.value)}
                    >
                       {data.terms_and_conditions}
                    </textarea>*/}

                    <TextEditor previousValue={data.terms_and_conditions} onChange={handleContentChange} />

                    <InputError
                        message={errors?.terms_and_conditions}
                        className="mt-2"
                    />

                </fieldset>
                <PrimaryButton type="submit" className="mt-4">Update</PrimaryButton>

            </form>
        </AuthenticatedLayout>
    );
}
