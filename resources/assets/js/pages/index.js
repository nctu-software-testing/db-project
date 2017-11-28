"use strict";
const ApiPrefix = require('../ApiPrefix');
let IndexPage = {
    init: () => {
        let carousel = $("#carousel");
        let id = carousel.prop('id');
        let indicators = carousel.find('.carousel-indicators');
        let inner = carousel.find('.carousel-inner');
        ajax("GET", 'images/banner/list.json')
            .then(data => {
                data.forEach((d, i)=>{
                    indicators.append(
                        `<li data-target="#${id}" data-slide-to="${i}"></li>`
                    );
                    inner.append(
                        `
                        <div class="carousel-item">
                            <img class="d-block w-100" src="${ApiPrefix}/images/banner/${d}" alt="slide">
                        </div>
                        `
                    );
                });

                indicators.find('>:first').addClass('active');
                inner.find('>:first').addClass('active');

                carousel.carousel({
                    interval: 2000
                });
            });
    },
};
module.exports = IndexPage;