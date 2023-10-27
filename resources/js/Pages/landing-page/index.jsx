import About from "./Components/About";
import Appfeatures from "./Components/Appfeatures";
import DownloadApp from "./Components/DownloadApp";
import Header from "./Components/Header";
import SeamlessProcess from "./Components/SeamlessProcess";
import ZoomCallAndCertificate from "./Components/ZoomCall";
import Banner from "./Components/banner";
import Footer from "./Components/footer";
import ContactUs from "@/Pages/landing-page/Components/ContactUs";

const LandingPage = () => {
    return (
        <div className="rt-wrapper">
            <Header />
            <Banner />
            <About />
            <Appfeatures />
            <SeamlessProcess />
            <ZoomCallAndCertificate />
            <DownloadApp />
            <ContactUs />
            <Footer />
        </div>
    );
};

export default LandingPage;
