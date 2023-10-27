import Header from "./Components/Header";
import Footer from "./Components/footer";
import 'react-quill/dist/quill.snow.css';

const TermsAndConditions = (props) => {
    console.log(props)
    return (
        <div className="rt-wrapper">
            <div className="rt-bannerbg-img w-full h-[100px]">
                <Header />
            </div>
            <div className="main">
                <div className="pt-16 sm:pt-20 lg:pt-[80px] min-h-[700px]">
                    <div className="container">
                        <h2 className="text-[#202322] font-gilroy-bold text-[2.7rem] md:text-[3.3rem] xl:text-[4rem]  leading-[4rem] md:leading-[5rem] lg:leading-[5.5rem] xl:leading-[5.813rem] mb-6 xl:mb-8">
                            Terms And Conditions
                        </h2>
                        <div className="font-gilroy-regular policy-text text-xl text-[#909191] mb-10" dangerouslySetInnerHTML={{ __html: props?.terms_and_conditions }}></div>


                    </div>
                </div>
            </div>
            <Footer />
        </div>
    );
};

export default TermsAndConditions;
