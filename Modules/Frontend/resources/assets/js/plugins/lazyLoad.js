export default {
    install(app) {
      console.log('LazyLoad plugin installed');
      
      app.directive('lazy', {
        mounted(el, binding) {
          console.log('Lazy directive mounted', binding.value);
          
          const loadImage = () => {
            console.log('Loading image:', binding.value);
            el.src = binding.value;
            observer.unobserve(el);
          };
          
          const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
              if (entry.isIntersecting) {
                console.log('Image in viewport:', binding.value);
                loadImage();
              }
            });
          }, {
            rootMargin: '100px',
            threshold: 0.1
          });
          
          console.log('Observing element');
          observer.observe(el);
        }
      });
    }
  };