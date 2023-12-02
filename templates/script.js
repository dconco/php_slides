const slides = [{
        title: 'Slide 1',
        content: 'This is the first slide.'
    },
    {
        title: 'Slide 2',
        content: 'This is the second slide.'
    },
    {
        title: 'Slide 3',
        content: 'This is the third slide.'
    },
];

const slidesContainer = document.getElementById('slides');

for (const slide of slides) {
    const slideEl = document.createElement('div');
    slideEl.classList.add('slide');

    const slideTitle = document.createElemeent('h2');
    slideTitle.textContent = slide.title;

    const slideContent = document.createElement('p');
    slideContent.textContent = slide.content;

    slideEl.appendChild(slideTitle);
    slideEl.appendChild(slideContent);

    slidesContainer.appendChild(slideEl);
}