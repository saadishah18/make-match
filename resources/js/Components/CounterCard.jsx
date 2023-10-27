

export default function CounterCard(props) {

  return (
    <>
        <div className="bg-white md:p-6 p-5 rounded-xl flex justify-between text-white">
            <div className="space-y-3">
                <h2 className="text-2xl tracking-wide pt-1">{props.title}</h2>
                <span className="block text-themecolor text-4xl">{props.value}</span>
            </div>
            <div>
                <div className="md:h-[60px] md:w-[60px] h-[40px] w-[40px] bg-themecolor rounded-full flex items-center justify-center text-gray-100 text-4xl ">
                {props.src}
                </div>
            </div>
        </div>
    </>
  )
}
