import React, {useEffect, useRef, useState} from 'react'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout'
import {Head, Link, usePage} from '@inertiajs/inertia-react'
import makeAnimated from 'react-select/animated'
import FullPageLoader from "@/Components/FullPageLoader";


export default function Dashboard(props) {
    console.log({props})
    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            header={
                <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                    Dashboard
                </h2>
            }
        >
            <Head title="Dashboard"/>
            <div className="at-teamsratingworking block lg:flex gap-4 justify-between">
                <div className="at-themebox at-teamsrankingchart">
                    <div className="at-themeboxtitle block sm:flex justify-between items-center pb-7 ">
                        <h4 className=" text-black font-product_sans_mediumregular">
                            Teams Rating
                        </h4>
                        <div className="block sm:flex justify-center items-center gap-4 sm:mt-0 mt-5">
                           {/* <Select
                                placeholder="Select year"
                                options={options}
                                className="at-chartselect sm:mb-0 mb-5"
                                onChange={async (e) => {
                                    await setYear(e.value);
                                    handleChartFilter(month, e.value)
                                }}
                                name="year_filter"
                            />
                            <Select
                                className="at-chartselect at-selectdepartment at-addemployeselect w-full"
                                classNamePrefix="select"
                                name="month_filter"
                                options={options1}
                                placeholder="Select Month"
                                onChange={async (e) => {
                                    await setMonth(e.value);
                                    handleChartFilter(e.value, year)
                                }}
                            />*/}
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    )
}
