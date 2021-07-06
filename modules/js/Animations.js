define(["dojo", "dojo/_base/declare"], (dojo, declare) => {
  return declare("fixtheteleporter.animations", ebg.core.gamegui, {
    slide(mobileElt, targetElt, options = {}) {
      let config = Object.assign(
        {
          duration: 800,
          delay: 0,
          destroy: false,
          attach: true,
          changeParent: true, // Change parent during sliding to avoid zIndex issue
          pos: null,
          className: "moving",
          from: null,
          clearPos: true,
          phantom: false,
        },
        options
      );
      config.phantomStart = config.phantomStart || config.phantom;
      config.phantomEnd = config.phantomEnd || config.phantom;

      // Handle phantom at start
      mobileElt = $(mobileElt);
      let mobile = mobileElt;
      if (config.phantomStart) {
        mobile = dojo.clone(mobileElt);
        dojo.attr(mobile, "id", mobileElt.id + "_animated");
        dojo.place(mobile, "game_play_area");
        this.placeOnObject(mobile, mobileElt);
        dojo.addClass(mobileElt, "phantom");
        config.from = mobileElt;
      }

      // Handle phantom at end
      targetElt = $(targetElt);
      let targetId = targetElt;
      if (config.phantomEnd) {
        targetId = dojo.clone(mobileElt);
        dojo.attr(targetId, "id", mobileElt.id + "_afterSlide");
        dojo.addClass(targetId, "phantomm");
        dojo.place(targetId, targetElt);
      }

      const newParent = config.attach ? targetId : $(mobile).parentNode;
      dojo.style(mobile, "zIndex", 5000);
      dojo.addClass(mobile, config.className);
      if (config.changeParent) this.changeParent(mobile, "game_play_area");
      if (config.from != null) this.placeOnObject(mobile, config.from);
      return new Promise((resolve, reject) => {
        const animation =
          config.pos == null
            ? this.slideToObject(
                mobile,
                targetId,
                config.duration,
                config.delay
              )
            : this.slideToObjectPos(
                mobile,
                targetId,
                config.pos.x,
                config.pos.y,
                config.duration,
                config.delay
              );

        dojo.connect(animation, "onEnd", () => {
          dojo.style(mobile, "zIndex", null);
          dojo.removeClass(mobile, config.className);
          if (config.phantomStart) {
            dojo.place(mobileElt, mobile, "replace");
            dojo.removeClass(mobileElt, "phantom");
            mobile = mobileElt;
          }
          if (config.changeParent) {
            if (config.phantomEnd) dojo.place(mobile, targetId, "replace");
            else this.changeParent(mobile, newParent);
          }
          if (config.destroy) dojo.destroy(mobile);
          if (config.clearPos && !config.destroy)
            dojo.style(mobile, { top: null, left: null, position: null });
          resolve();
        });
        animation.play();
      });
    },

    changeParent(mobile, new_parent, relation) {
      if (mobile === null) {
        console.error("attachToNewParent: mobile obj is null");
        return;
      }
      if (new_parent === null) {
        console.error("attachToNewParent: new_parent is null");
        return;
      }
      if (typeof mobile == "string") {
        mobile = $(mobile);
      }
      if (typeof new_parent == "string") {
        new_parent = $(new_parent);
      }
      if (typeof relation == "undefined") {
        relation = "last";
      }
      var src = dojo.position(mobile);
      dojo.style(mobile, "position", "absolute");
      dojo.place(mobile, new_parent, relation);
      var tgt = dojo.position(mobile);
      var box = dojo.marginBox(mobile);
      var cbox = dojo.contentBox(mobile);
      var left = box.l + src.x - tgt.x;
      var top = box.t + src.y - tgt.y;
      this.positionObjectDirectly(mobile, left, top);
      box.l += box.w - cbox.w;
      box.t += box.h - cbox.h;
      return box;
    },

    positionObjectDirectly(mobileObj, x, y) {
      // do not remove this "dead" code some-how it makes difference
      dojo.style(mobileObj, "left"); // bug? re-compute style
      // console.log("place " + x + "," + y);
      dojo.style(mobileObj, {
        left: x + "px",
        top: y + "px",
      });
      dojo.style(mobileObj, "left"); // bug? re-compute style
    },
  });
});
