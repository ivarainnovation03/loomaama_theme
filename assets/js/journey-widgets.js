(function () {
  'use strict';

  function clamp01(value) {
    return Math.min(1, Math.max(0, value));
  }

  function sectionIndex(section, itemCount) {
    var rect = section.getBoundingClientRect();
    var scrollable = section.offsetHeight - window.innerHeight;
    if (scrollable <= 0 || itemCount <= 0) return 0;
    var progress = clamp01(-rect.top / scrollable);
    return Math.min(itemCount - 1, Math.floor(progress * itemCount));
  }

  function updateFounder(section) {
    if (!section) return;

    var steps = section.querySelectorAll('.story-step');
    var bars = section.querySelectorAll('.founder-progress span');
    var images = section.querySelectorAll('.founder-image');
    if (!steps.length) return;

    var index = sectionIndex(section, steps.length);
    section.dataset.step = String(index);

    steps.forEach(function (element, itemIndex) {
      element.classList.toggle('active', itemIndex === index);
    });

    bars.forEach(function (element, itemIndex) {
      element.classList.toggle('active', itemIndex === index);
    });

    images.forEach(function (element, itemIndex) {
      element.classList.toggle('active', itemIndex === index);
    });

    var caption = section.querySelector('.founder-caption');
    var activeImage = images[index];
    if (caption && activeImage) {
      caption.textContent = activeImage.getAttribute('data-caption') || '';
    }
  }

  function updateMountain(section) {
    if (!section) return;

    var steps = section.querySelectorAll('.mountain-copy .step');
    var backgrounds = section.querySelectorAll('.mountain-bg');
    if (!steps.length) return;

    var index = sectionIndex(section, steps.length);
    section.dataset.step = String(index);

    steps.forEach(function (element, itemIndex) {
      element.classList.toggle('active', itemIndex === index);
    });

    backgrounds.forEach(function (element, itemIndex) {
      element.classList.toggle('active', itemIndex === index);
    });

    var counter = section.querySelector('.mountain-progress');
    if (counter) {
      counter.textContent = String(index + 1).padStart(2, '0') + ' / ' + String(steps.length).padStart(2, '0');
    }
  }

  function updateForest(section) {
    if (!section) return;

    var steps = section.querySelectorAll('.forest-copy .step');
    var tags = section.querySelectorAll('.tag[data-tag-index]');
    if (!steps.length) return;

    var index = sectionIndex(section, steps.length);
    section.dataset.step = String(index);

    steps.forEach(function (element, itemIndex) {
      element.classList.toggle('active', itemIndex === index);
    });

    tags.forEach(function (tag, tagOrder) {
      var tagIndex = parseInt(tag.getAttribute('data-tag-index'), 10);
      var show = !Number.isNaN(tagIndex) && tagIndex <= index;
      var wasVisible = tag.dataset.lmVisible === '1';

      tag.style.opacity = show ? '1' : '0';
      tag.style.transform = show ? 'translateY(0) scale(1)' : 'translateY(20px) scale(0.9)';
      tag.dataset.lmVisible = show ? '1' : '0';

      if (show && !wasVisible) {
        window.setTimeout(function () {
          tag.classList.add('pulse');
          window.setTimeout(function () {
            tag.classList.remove('pulse');
          }, 600);
        }, 100 + tagOrder * 100);
      } else if (!show) {
        tag.classList.remove('pulse');
      }
    });
  }

  function initHero(widget) {
    var video = widget.querySelector('[data-lm-role="hero-video"]');
    var toggle = widget.querySelector('[data-lm-role="sound-toggle"]');
    if (!video || !toggle || toggle.dataset.lmReady === '1') return;

    toggle.dataset.lmReady = '1';

    var onIcon = toggle.querySelector('.sound-on');
    var offIcon = toggle.querySelector('.sound-off');
    var label = toggle.querySelector('.sound-label');
    var soundOnLabel = toggle.getAttribute('data-sound-on-label') || 'Mute';
    var soundOffLabel = toggle.getAttribute('data-sound-off-label') || 'Unmute';

    function updateButton() {
      var isOn = !video.muted;
      toggle.classList.toggle('sound-on', isOn);
      if (onIcon) onIcon.style.display = isOn ? 'block' : 'none';
      if (offIcon) offIcon.style.display = isOn ? 'none' : 'block';
      if (label) label.textContent = isOn ? soundOnLabel : soundOffLabel;
    }

    var hideTimeout = null;
    var isHovering = false;
    var lastScrollY = window.scrollY;

    function showToggle() {
      toggle.style.opacity = '1';
      toggle.style.transform = 'translateY(0)';
      window.clearTimeout(hideTimeout);
    }

    function hideToggle() {
      if (isHovering) return;
      hideTimeout = window.setTimeout(function () {
        toggle.style.opacity = '0.4';
        toggle.style.transform = 'translateY(10px)';
      }, 3000);
    }

    toggle.addEventListener('click', function () {
      video.muted = !video.muted;
      if (!video.muted) {
        video.volume = 0.3;
        video.play().catch(function () {});
      }
      updateButton();
      showToggle();
      hideToggle();
    });

    window.addEventListener('scroll', function () {
      var currentScrollY = window.scrollY;
      if (Math.abs(currentScrollY - lastScrollY) > 50) {
        showToggle();
        hideToggle();
        lastScrollY = currentScrollY;
      }
    }, { passive: true });

    toggle.addEventListener('mouseenter', function () {
      isHovering = true;
      showToggle();
    });

    toggle.addEventListener('mouseleave', function () {
      isHovering = false;
      hideToggle();
    });

    updateButton();
    window.setTimeout(hideToggle, 3000);
  }

  function prepareStickyAncestors() {
    var selector = [
      '.loomaama-section-widget--founder-story .founder-story',
      '.loomaama-section-widget--mountain-story .mountain-story',
      '.loomaama-section-widget--forest-story .forest-story'
    ].join(',');

    document.querySelectorAll(selector).forEach(function (section) {
      var ancestor = section.parentElement;

      while (ancestor && ancestor !== document.body && ancestor !== document.documentElement) {
        var styles = window.getComputedStyle(ancestor);
        var overflowValues = [styles.overflow, styles.overflowX, styles.overflowY];
        var blocksSticky = overflowValues.some(function (value) {
          return value === 'hidden' || value === 'auto' || value === 'scroll' || value === 'overlay';
        });
        var hasContainingEffect =
          styles.transform !== 'none' ||
          styles.filter !== 'none' ||
          styles.perspective !== 'none' ||
          /(?:paint|layout|strict|content)/.test(styles.contain || '');

        if (blocksSticky || hasContainingEffect) {
          ancestor.classList.add('lm-journey-sticky-ancestor');
        }

        ancestor = ancestor.parentElement;
      }
    });
  }

  function updateAll() {
    document.querySelectorAll('.loomaama-section-widget .founder-story').forEach(updateFounder);
    document.querySelectorAll('.loomaama-section-widget .mountain-story').forEach(updateMountain);
    document.querySelectorAll('.loomaama-section-widget .forest-story').forEach(updateForest);
  }

  function init() {
    prepareStickyAncestors();
    document.querySelectorAll('.loomaama-section-widget--hero').forEach(initHero);

    document.querySelectorAll('.loomaama-section-widget .founder-story').forEach(function (section) {
      section.dataset.step = '0';
      section.querySelectorAll('.founder-image').forEach(function (image, index) {
        image.classList.toggle('active', index === 0);
      });
      updateFounder(section);
    });

    document.querySelectorAll('.loomaama-section-widget .mountain-story').forEach(function (section) {
      section.dataset.step = '0';
      section.querySelectorAll('.mountain-bg').forEach(function (background, index) {
        background.classList.toggle('active', index === 0);
      });
      updateMountain(section);
    });

    document.querySelectorAll('.loomaama-section-widget .forest-story').forEach(function (section) {
      section.dataset.step = '0';
      updateForest(section);
    });

    updateAll();
  }

  var ticking = false;
  function scheduleUpdate() {
    if (ticking) return;
    ticking = true;
    window.requestAnimationFrame(function () {
      updateAll();
      ticking = false;
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

  window.addEventListener('scroll', scheduleUpdate, { passive: true });
  window.addEventListener('resize', scheduleUpdate, { passive: true });
  window.addEventListener('load', function () {
    init();
    updateAll();
  });

  // Restore correct states after back/forward cache navigation.
  window.addEventListener('pageshow', function () {
    prepareStickyAncestors();
    scheduleUpdate();
  });

  // Supports Customizer selective refresh and builders that insert widgets
  // after DOMContentLoaded.
  if ('MutationObserver' in window) {
    var observerQueued = false;
    var observer = new MutationObserver(function (mutations) {
      var containsJourneyWidget = mutations.some(function (mutation) {
        return Array.prototype.some.call(mutation.addedNodes || [], function (node) {
          return node.nodeType === 1 && (
            node.matches && node.matches('.loomaama-section-widget') ||
            node.querySelector && node.querySelector('.loomaama-section-widget')
          );
        });
      });

      if (!containsJourneyWidget || observerQueued) return;
      observerQueued = true;
      window.requestAnimationFrame(function () {
        init();
        observerQueued = false;
      });
    });

    observer.observe(document.documentElement, { childList: true, subtree: true });
  }
})();
