
import 'dart:collection';
import 'dart:convert';

import 'package:elmenu/src/APIConfig.dart';
import 'package:http/http.dart';
import 'package:rxdart/rxdart.dart';

import 'DataModels/Menu.dart';
import 'DataModels/MenuCategory.dart';


class BLoC{

  final _menuSubject = BehaviorSubject<UnmodifiableListView<MenuItem>>();

  final _categorySubject = BehaviorSubject<UnmodifiableListView<MenuCategory>>();

  List<MenuItem> _menuItems = List<MenuItem>();
  List<MenuCategory> _categoryItems = List<MenuCategory>();
  Stream<UnmodifiableListView<MenuItem>> get Menu => _menuSubject.stream;
  Stream<UnmodifiableListView<MenuCategory>> get Category => _categorySubject.stream;
  
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

  BLoC(){
    _getCategoryList().then((_){
      _categorySubject.add(UnmodifiableListView(_categoryItems));
    });
    _getMenuItemsList().then((_){
      _menuSubject.add(UnmodifiableListView(_menuItems));
    });
  }
  
}