import React, {useEffect, useState} from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import CounterCard from '@/Components/CounterCard';
import { AiFillContacts } from "react-icons/ai";
import { BsCalendarWeekFill, BsFillPeopleFill } from "react-icons/bs";
import { FaUserAlt } from "react-icons/fa";

const countdata = [
    {
      title: "users",
      value: "10",
      src: <BsFillPeopleFill />,
    },
    {
      title: "Nikah",
      value: "6",
      src: <BsCalendarWeekFill />,
    },
    {
      title: "Imams",
      value: "12",
      src: <FaUserAlt />,
    },

  ];

export default function Dashboard(props) {

    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>}
        >
            <Head title="Dashboard" />
            <div className="mb-6 block md:flex items-center justify-between">
                <h3 className="text-black text-[1.75rem] leading-7 font-product_sans_mediumregular">Dashboard</h3>
            </div>
            <div className="grid lg:grid-cols-3 grid-cols-1 md:gap-8 gap-4 w-full">
                {countdata.map((v, k) => {
                    return <CounterCard key={k} {...v} />;
                })}
            </div>
        </AuthenticatedLayout>
    );
}
