import $ from 'jquery';

class Menu{
  constructor(){
    this.primaryNav = $('.primary-nav');
    this.menuIcon = $('.site-header__menu-icon');
    this.events();
  }

  events(){
    this.menuIcon.click(this.toggleTheMenu.bind(this));
  }

  toggleTheMenu(){
    this.primaryNav.toggleClass("primary-nav--is-visible");
    this.menuIcon.toggleClass("site-header__menu-icon--close-x");
  }
}

export default Menu;
