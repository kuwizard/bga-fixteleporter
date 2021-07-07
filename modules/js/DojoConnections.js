define(['dojo', 'dojo/_base/declare'], (dojo, declare) => {
  return declare('fixtheteleporter.dojoconnections', ebg.core.gamegui, {
    constructor() {
      this._connected = {};
    },

    connect(element, func, param = null, id = null) {
      dojo.addClass(element, 'connected');
      if (id === null) {
        // This is a flip button and we don't care about id
        id = this.rand();
        while (Object.keys(this._connected).map(Number).includes(id)) {
          id = this.rand();
        }
      }
      this._connected[id] = dojo.connect(element, 'onclick', (evt) => {
        evt.preventDefault();
        evt.stopPropagation();
        func(param);
      });
    },

    disconnect(ids) {
      if (!Array.isArray(ids)) {
        ids = [ids];
      }
      ids.forEach((id) => {
        dojo.disconnect(this._connected[id]);
        delete this._connected[id];
      });
    },

    dojoDisconnectAllEvents() {
      dojo.query('.connected').forEach((item) => {
        dojo.removeClass(item, 'connected');
      });
      this.disconnect(Object.keys(this._connected));
    },

    rand() {
      return Math.floor(Math.random() * 10) + 10;
    },
  });
});
