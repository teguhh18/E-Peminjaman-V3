<style>
    @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap");

    * {
        font-family: "Poppins", sans-serif;
    }

    .grow-today {
        padding: 50px 0;
    }

    .grow-today .gap {
        row-gap: 48px;
    }

    .grow-today .card-grow {
        border-radius: 20px;
        background-color: #FFFFFF;
        overflow: hidden;
        position: relative;
    }

    .grow-today .card-grow img {
        width: 100%;
        height: 200px;
        -o-object-fit: cover;
        object-fit: cover;
    }

    .grow-today .card-grow .badge-pricing {
        position: absolute;
        z-index: 1;
        right: 16px;
        top: 16px;
        text-align: center;
        padding: 5px 13px;
        color: #01FB34;
        font-weight: 500;
        font-size: 14px;
        background-color: #151A26;
        border-radius: 41px;
    }

    .grow-today .card-grow .badge-category {
        position: absolute;
        z-index: 1;
        left: 16px;
        top: 16px;
        text-align: center;
        padding: 5px 13px;
        color: white;
        font-weight: 500;
        font-size: 14px;
        background-color: #151A26;
        border-radius: 41px;
    }

    .grow-today .card-grow .card-content {
        padding: 20px;
    }

    .grow-today .card-grow .card-content .card-title {
        font-weight: 500;
        font-size: 18px;
        color: #151A26;
    }

    .grow-today .card-grow .card-content .card-subtitle {
        font-weight: 400;
        font-size: 14px;
        color: #A3A5AA;
        margin: 8px 0 30px;
    }

    .grow-today .card-grow .card-content .description {
        font-weight: 500;
        font-size: 14px;
        color: #151A26;
    }

    .title {
        font-size: 32px;
        color: #151A26;
        font-weight: 500;
    }

    .sub-title {
        font-size: 27px;
        font-weight: 600;
    }

    .text-gradient-pink {
        background-color: #F32FB8;
        background-image: linear-gradient(113.4deg, #F32FB8 0%, #FDD7BE 100%);
        background-image: conic-gradient(113.4deg, #F32FB8 0%, #FDD7BE 100%);
        background-size: 100%;
        background-repeat: no-repeat;
        background-clip: text;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        -moz-background-clip: text;
        -moz-text-fill-color: transparent;
    }

    .details-content {
        margin-top: -240px;
        padding-bottom: 70px;
    }

    .details-content .gap {
        -moz-column-gap: 100px;
        column-gap: 100px;
        row-gap: 75px;
    }

    .details-content .description {
        margin-top: 24px;
        max-width: 596px;
    }

    .details-content .description .headline {
        font-weight: 600;
        font-size: 42px;
        color: #FFFFFF;
    }

    .details-content .description .event-details {
        margin-top: 140px;
    }

    .details-content .description h6 {
        color: #151A26;
        font-size: 20px;
        font-weight: 600;
    }

    .details-content .description .details-paragraph,
    .details-content .description {
        color: #252837;
        font-size: 16px;
        font-weight: 400;
        line-height: 34px;
        margin-top: 20px;
    }

    .details-content .description {
        margin-top: 50px;
        line-height: 26px;
    }

    .details-content .description div {
        margin-top: 16px;
    }

    .details-content .description {
        margin-top: 50px;
    }

    .details-content .description h6 {
        margin-bottom: 20px;
    }

    .details-content .description {
        padding: 8px;
        border-radius: 18px;
        border: 1px solid #DFDFDF;
        width: -webkit-max-content;
        width: -moz-max-content;
        width: max-content;
    }

    .details-content .description {
        position: relative;
        overflow: hidden;
    }

    .details-content .description img {
        border-radius: 18px;
        width: 442px;
        height: 295px;
        -o-object-fit: cover;
        object-fit: cover;
    }

    .details-content .description .absolute {
        position: absolute;
        z-index: 1;
        left: 0;
        top: 0;
        bottom: 0;
        right: 0;
        border-radius: 18px;
        transition: all 0.3s;
    }

    .details-content .description .absolute .btn-navy {
        padding: 8px 12px !important;
        font-size: 10px !important;
        opacity: 0;
    }

    .details-content .description .absolute .btn-navy:hover {
        box-shadow: 0px 4px 15px rgba(19, 19, 29, 0.35);
    }

    .details-content .card-event {
        padding: 24px;
        background-color: #FFFFFF;
        border-radius: 24px;
        border: 1px solid #DFDFDF;
        height: -webkit-max-content;
        height: -moz-max-content;
        height: max-content;
    }

    .details-content .card-event h6 {
        color: #151A26;
        font-size: 18px;
        font-weight: 500;
        margin-bottom: 0;
    }

    .details-content .card-event .speaker-name {
        color: #151A26;
        font-size: 16px;
        font-weight: 500;
    }

    .details-content .card-event .occupation,
    .details-content .card-event .price span {
        color: #A3A5AA;
        font-size: 14px;
        font-weight: 400;
    }

    .details-content .card-event hr {
        margin: 30px 0;
        color: #DFDFDF;
    }

    .details-content .card-event .price {
        color: #151A26;
        font-size: 42px;
        font-weight: 700;
    }

    .details-content .card-event .price span {
        font-size: 16px;
    }

    .details-content .card-event .card-details {
        color: #151A26;
        font-size: 16px;
        font-weight: 400;
        margin-bottom: 16px;
    }

    .details-content .card-event .btn-green {
        margin-top: 14px;
        width: 302px;
    }

    @media (max-width: 1200px) {
        .preview-image {
            height: 850px;
        }
    }

    @media (max-width: 992px) {
        .preview-image {
            height: 750px;
        }

        .preview-image .img-content {
            width: 70%;
        }

        .details-content .card-event {
            margin: 0 auto;
        }
    }

    @media (max-width: 500px) {
        .preview-image {
            height: 650px;
        }

        .preview-image .img-content {
            width: 90%;
        }

        .details-content {
            margin-top: -280px;
        }

        .details-content .description {
            max-width: 100%;
        }

        .details-content .description {
            margin-top: 50px;
        }

        .details-content .description h6 {
            margin-bottom: 20px;
        }

        .details-content .description {
            width: 100%;
        }

        .details-content .description {
            max-width: 100%;
        }
    }
</style>
