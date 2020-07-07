
import 'dart:collection';
import 'dart:convert';



import 'package:elmenu/src/Config/APIConfig.dart';
import 'package:http/http.dart';
import 'package:rxdart/rxdart.dart';

import 'DataModels/Menu.dart';
import 'DataModels/Offer.dart';
import 'DataModels/MenuCategory.dart';


class BLoC{

  final _menuSubject = BehaviorSubject<UnmodifiableListView<MenuItem>>();
  final _categorySubject = BehaviorSubject<UnmodifiableListView<MenuCategory>>();
  final _offerSubject = BehaviorSubject<UnmodifiableListView<offer>>();

  List<MenuItem> _menuItems = List<MenuItem>();
  List<MenuCategory> _categoryItems = List<MenuCategory>();
  List<offer> _offersList = List<offer>();

  Stream<UnmodifiableListView<MenuItem>> get Menu => _menuSubject.stream;
  Stream<UnmodifiableListView<MenuCategory>> get Category => _categorySubject.stream;
  Stream<UnmodifiableListView<offer>> get Offer => _offerSubject.stream;

  Future<Null> _getMenuItemsList() async{
    List<MenuItem> list;
    Response respose = await get(Uri.encodeFull(APIConfig.getMenu));
    final jsonResponse = json.decode(respose.body);
    final jsonMenu = jsonResponse['menu'] as List;
    list = jsonMenu.map<MenuItem>((e) => MenuItem.fromJson(e)).toList();
    _menuItems = list;
  }

  Future<Null> _getCategoryList() async{
    List<MenuCategory> list;
    Response respose = await get(Uri.encodeFull(APIConfig.getMenuCategory));
    final jsonResponse = json.decode(respose.body);
    final jsonMenu = jsonResponse['category'] as List;
    list = jsonMenu.map<MenuCategory>((e) => MenuCategory.fromJson(e)).toList();
    _categoryItems = list;
  }

  Future<Null> _getOffersList() async{
    List<offer> list;
    Response response = await get(Uri.encodeFull(APIConfig.getOffers));
    final jsonResponse = json.decode(response.body);
    final jsonOffers = jsonResponse['offer'] as List;
    list = jsonOffers.map<offer>((e) => offer.fromJson(e)).toList();
    _offersList = list;
  }

  // ignore: non_constant_identifier_names
  Future<Null> Refresh() async{
    _offersList.clear();
    _menuItems.clear();
    _categoryItems.clear();
    _getCategoryList().then((_){
      _categorySubject.add(UnmodifiableListView(_categoryItems));
    });
    _getMenuItemsList().then((_){
      _menuSubject.add(UnmodifiableListView(_menuItems));
    });
    _getOffersList().then((_){
      _offerSubject.add(UnmodifiableListView(_offersList));
    });
  }

  BLoC(){
    _getCategoryList().then((_){
      _categorySubject.add(UnmodifiableListView(_categoryItems));
    });
    _getMenuItemsList().then((_){
      _menuSubject.add(UnmodifiableListView(_menuItems));
    });
    _getOffersList().then((_){
      _offerSubject.add(UnmodifiableListView(_offersList));
    });
  }
  
}