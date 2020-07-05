import 'dart:collection';

import 'package:elmenu/src/DataModels/Menu.dart';
import 'package:elmenu/src/DataModels/MenuCategory.dart';
import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart';
import 'package:flutter/rendering.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';

import '../BLoC.dart';

class MenuPageView extends StatefulWidget {
  BLoC bLoC;

  MenuPageView({Key key, this.bLoC});

  @override
  _MenuPageViewState createState() => _MenuPageViewState();
}

class _MenuPageViewState extends State<MenuPageView> {
  String init ;
  String selected;
  @override
  Widget build(BuildContext context) {
    return Container(
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.center,
        children: [
          Divider(height: 1,thickness: 1,),
          Padding(
            padding: const EdgeInsets.all(4.0),
            child: Container(
              height: 35,
              child: Center(
                child: StreamBuilder<UnmodifiableListView<MenuCategory>>(
                    stream: widget.bLoC.Category,
                    initialData: UnmodifiableListView<MenuCategory>([]),
                    builder: (context, snapshot) {
                      //var ls = init == null?snapshot.data.toList():snapshot.data.where((e) => e.category==init).toList();
                      return Center(
                        child: ListView(
                          scrollDirection: Axis.horizontal,
                          children: snapshot.data.map((e) => Padding(
                            padding: const EdgeInsets.only(left:3.0,right: 3.0),
                            child: FlatButton(
                              onPressed: () { setState(() {
                                selected =e.category_name;
                                init = e.category_name;
                              }); },
                              child: Center(child: Text(e.category_name,style: TextStyle(color: selected==e.category_name?Colors.white:null,fontFamily: 'Cairo',fontWeight: FontWeight.bold),)),
                              color: selected==e.category_name?Colors.red.shade300:null,
                              shape: RoundedRectangleBorder(  borderRadius: BorderRadius.circular(18.0),
                                  //side: BorderSide(color: Colors.red)
                                ),
                            ),
                          ),).toList(),
                        ),
                      );
                    }),
              ),
            ),
          ),
          SizedBox(height: 2,),
          Divider(height: 1,thickness: 1,),
          Expanded(
            child: StreamBuilder<UnmodifiableListView<MenuItem>>(
                stream: widget.bLoC.Menu,
                initialData: UnmodifiableListView<MenuItem>([]),
                builder: (context, snapshot) {
                  var ls = init == null?snapshot.data.toList():snapshot.data.where((e) => e.category==init).toList();
                      return ListView(
                        children: ls.map(menu_item).toList(),
                      );
                    }),
          )
        ],
      ),
    );
  }


  Widget menu_item(MenuItem item){
    return Padding(
      padding: const EdgeInsets.only(top:7.0,bottom: 7.0, left: 8.0,right: 8.0),
      child: InkWell(
        child: Container(
          decoration: BoxDecoration(
            borderRadius: BorderRadius.all(Radius.circular(20)),
            color: Colors.white,
            boxShadow: [BoxShadow(
              color: Colors.grey.withOpacity(0.5),
              spreadRadius: 1,
              blurRadius: 10,
              offset: Offset(0,3),
            )],
          ),
          child: Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Hero(
                tag: item.item_id,
                child: Container(
                  width: 120,
                  height: 120,
                  decoration: BoxDecoration(
                      borderRadius: BorderRadius.only(topLeft: Radius.circular(20),bottomLeft: Radius.circular(20)),
                      image: DecorationImage(
                          image: NetworkImage(item.item_photo),
                          fit: BoxFit.cover
                      )
                  ),
                ),
              ),
              Container(
                width: MediaQuery.of(context).size.width-140,
                height: 120,
                child: Padding(
                  padding: const EdgeInsets.symmetric(horizontal: 15,vertical: 10),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.center,
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    mainAxisSize: MainAxisSize.max,
                    children: <Widget>[
                      Expanded(
                        flex: 3,
                        child: Column(
                          children: <Widget>[
                            Container(
                              decoration: BoxDecoration(
                                  border: Border.all(width: 3,color: Colors.red.shade300),
                                  borderRadius: BorderRadius.all(Radius.circular(5))
                              ),
                              child: Text(
                                ' ${item.item_name} ',
                                overflow: TextOverflow.fade,
                                softWrap: false,
                                style: TextStyle(color: Theme.of(context).primaryColor,fontSize: 14,fontWeight: FontWeight.bold,fontFamily: 'Cairo'),
                              ),
                            ),
                            SizedBox(
                              height: 7,
                            ),
                            Text(
                              item.item_details,
                              overflow: TextOverflow.fade,
                              softWrap: true,
                              maxLines: 2,
                              style: Theme.of(context).textTheme.caption,
                            ),
                            SizedBox(
                              height: 4,
                            ),
                          ],
                        ),
                      ),
                      SizedBox(width: 15,),
                      Expanded(
                        child: Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: <Widget>[
                            Text(
                              "price: ${item.item_price}",
                              //overflow: TextOverflow.fade,
                              maxLines: 1,
                              softWrap: false,
                              style: TextStyle(color: Colors.grey,fontFamily: 'cairo',fontWeight: FontWeight.bold),
                            ),
                            Text(
                              "Press here for More Details",
                              overflow: TextOverflow.fade,
                              maxLines: 1,
                              softWrap: false,
                              style: TextStyle(color: Colors.blue,fontFamily: 'cairo',fontWeight: FontWeight.bold,fontSize: 10),
                            ),
                          ],
                        ),
                      ),
                    ],
                  ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
