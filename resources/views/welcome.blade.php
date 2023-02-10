<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="css/style.css"/>
    <link rel="stylesheet" href="css/spinner.css"/>

    <script src="js/main.js" defer></script>
    <title>OpenAI Image Generator by Native brains</title>
</head>
<body>
<header>
    <div class="navbar">
        <div class="logo">
            <h2>OpenAI Image Generator by Native brains</h2>
        </div>
    </div>
</header>

<main style="display: flex">
    <section class="showcase">
        <form id="image-form">
            <h1>Describe An Image</h1>
            <div class="form-control">
                <input type="text" id="prompt" placeholder="Enter Text"/>
            </div>
            <!-- size -->
            <div class="form-control">
                <select name="size" id="size">
                    <option value="small">Small</option>
                    <option value="medium" selected>Medium</option>
                    <option value="large">Large</option>
                </select>
            </div>
            <div class="form-control">
                <select name="no_of_images" id="no_of_images">
                    <option selected value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                </select>
            </div>
            <button type="submit" class="btn">Generate</button>
        </form>
    </section>

    <section class="image">
        <div class="image-container">
            <h2 style="text-align: center">Image Preview</h2>
            <p class="msg"></p>
            <div class="gallery hide">
                <figure class="gallery__item gallery__item--1">
                    <img src=""
                         alt="" class="gallery__img hide">
                </figure>
                <figure class="gallery__item gallery__item--1">
                    <img src=""
                         alt="" class="gallery__img hide">
                </figure>
                <figure class="gallery__item gallery__item--1">
                    <img src=""
                         alt="" class="gallery__img hide">
                </figure>
                <figure class="gallery__item gallery__item--1">
                    <img src=""
                         alt="" class="gallery__img hide">
                </figure>
            </div>
        </div>
    </section>
</main>

<div class="spinner"></div>
</body>
<script>
  function onSubmit(e) {
    e.preventDefault();

    document.querySelector('.gallery').classList.add('hide')
    document.querySelector('.msg').textContent = '';
    document.querySelectorAll('.gallery__img').forEach((el, index) => {
      el.src = ''
      el.classList.add('hide')
    })

    const prompt = document.querySelector('#prompt').value;
    const size = document.querySelector('#size').value;
    const n = document.querySelector('#no_of_images').value;

    if (prompt === '') {
      alert('Please add some text');
      return;
    }

    generateImageRequest(prompt, size, n);
  }

  async function generateImageRequest(prompt, size, n) {
    try {
      showSpinner();

      const response = await fetch('/openai/generate-image', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
          n,
          prompt,
          size,
        }),
      });

      if (!response.ok) {
        removeSpinner();
        throw new Error('That image could not be generated');
      }

      const {data} = await response.json();

      document.querySelectorAll('.gallery__img').forEach((el, index) => {
        if (data[index]) {
          el.src = data[index]
          el.classList.remove('hide')
        }
      })

      document.querySelector('.gallery').classList.remove('hide')

      removeSpinner();
    } catch (error) {
      document.querySelector('.msg').textContent = error;
    }
  }

  function showSpinner() {
    document.querySelector('.spinner').classList.add('show');
  }

  function removeSpinner() {
    document.querySelector('.spinner').classList.remove('show');
  }

  document.querySelector('#image-form').addEventListener('submit', onSubmit);

</script>
</html>
