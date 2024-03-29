import 'dart:collection';

import 'package:elmenu/src/Config/Config.dart';
import 'package:elmenu/src/DataModels/Menu.dart';
import 'package:elmenu/src/DataModels/MenuCategory.dart';
import 'package:elmenu/src/Widgets/FloatedCard.dart';
import 'package:floating_pullup_card/floating_layout.dart';
import 'package:floating_pullup_card/pullup_card.dart';
import 'package:floating_pullup_card/types.dart';
import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart';
import 'package:flutter/rendering.dart';
import 'package:flutter/widgets.dart';
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

  FloatingPullUpState _floatingCardState = FloatingPullUpState.hidden;
  bool _customDragHandle = true;
  bool _customCollapsedOffset = false;
  bool _autoPadd = true;
  bool _withOverlay = true;
  bool _customUncollapsedOffset = false;

  String title = '';
  String discrption='';
  String photo='https://picsum.photos/200/300';
  String price='';

  @override
  Widget build(BuildContext context) {
    return RefreshIndicator(
      onRefresh: widget.bLoC.RefreshMenu,
      child: FloatingPullUpCardLayout(
        dismissable: true,
        onOutsideTap: (){
          setState(() {
            _floatingCardState = FloatingPullUpState.hidden;
          });
        },
        state: _floatingCardState,
        dragHandleBuilder: _customDragHandle ? _customDragHandleBuilder : null,
        cardBuilder: _customCardBuilder,
        collpsedStateOffset:
        _customCollapsedOffset ? (maxHeight, _) => maxHeight * .75 : null,
        uncollpsedStateOffset:
        _customUncollapsedOffset ? (maxHeight) => maxHeight * .05 : null,
        autoPadding: _autoPadd,
        withOverlay: _withOverlay,
        body: Container(
          // padding: EdgeInsets.all(16),
          child: SingleChildScrollView(
            child: Column(
              children: <Widget>[
                Stack(
                  alignment: Alignment.bottomCenter,
                  children: [
                    Container(
                      width: MediaQuery.of(context).size.width,
                      height: 120,
                      decoration: BoxDecoration(
                        //borderRadius: BorderRadius.circular(100),
                          image: DecorationImage(
                            image: NetworkImage(photo),
                            fit: BoxFit.cover,
                          )
                      ),
                    ),
                    Container(
                      decoration: BoxDecoration(
                        gradient: LinearGradient(
                          colors: [Color(0x00f5f5f5),Color(0xfff5f5f5)],
                          begin: Alignment.topCenter,
                          end: Alignment.bottomCenter,
                        ),
                      ),
                      height: 70,
                    ),
                  ],
                ),
                SizedBox(
                  height: 10,
                ),
                Container(
                  decoration: BoxDecoration(
                      border: Border.all(width: 3,color: Colors.red.shade300),
                      borderRadius: BorderRadius.all(Radius.circular(5))
                  ),
                  child: Text(
                    ' ${title} ',
                    overflow: TextOverflow.fade,
                    softWrap: false,
                    style: TextStyle(color: Theme.of(context).primaryColor,fontSize: 17,fontWeight: FontWeight.bold,fontFamily: 'Cairo'),
                  ),
                ),
                SizedBox(
                  height: 10,
                ),
                Padding(
                  padding: const EdgeInsets.all(5.0),
                  child: Container(

                    child: Text(
                      ' ${discrption} ',
                      //overflow: TextOverflow.fade,
                      softWrap: true,
                      style: TextStyle(color: Colors.black87,fontSize: 13,fontFamily: 'Baloo2'),
                    ),
                  ),
                ),
                SizedBox(height: 10,),
                Container(
                  decoration: BoxDecoration(
                      border: Border(bottom: BorderSide(width: 3,color: Colors.red.shade300)),
                  ),
                  child: Text(
                    ' Price: ${price} ',
                    overflow: TextOverflow.fade,
                    softWrap: false,
                    style: TextStyle(color: Theme.of(context).primaryColor,fontSize: 13,fontWeight: FontWeight.bold,fontFamily: 'Cairo'),
                  ),
                ),
              ],
            ),
          ),
        ),
        child: Container(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.center,
            children: [
              Divider(height: 1,thickness: 1,),
              // Category Buttons List Container
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
                                  child: Text(e.category_name,style: TextStyle(color: selected==e.category_name?Colors.white:null,fontFamily: 'Cairo',fontWeight: FontWeight.bold),),
                                  color: selected==e.category_name?Colors.red.shade300:Colors.grey.shade400.withOpacity(0.45),
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
              // Page Body (Menu Items Grid View List)
              Expanded(
                child: StreamBuilder<UnmodifiableListView<MenuItem>>(
                    stream: widget.bLoC.Menu,
                    initialData: UnmodifiableListView<MenuItem>([]),
                    builder: (context, snapshot) {
                      var ls = init == null?snapshot.data.toList():snapshot.data.where((e) => e.category==init).toList();
                      return ls.isEmpty
                          ? Container(child: Center(child: Column(
                                  mainAxisAlignment: MainAxisAlignment.center,
                                  crossAxisAlignment: CrossAxisAlignment.center,
                                  children: [
                                    Text('Error!\nPlease Check Your Internet Connection!',style: TextStyle(color: Colors.grey,fontSize: 17),),
                                    FlatButton(onPressed: (){widget.bLoC.RefreshMenu();}, child: Text("Retry",style: TextStyle(color: Colors.white,fontSize: 15),),color: Colors.grey,)
                                  ],
                                )))
                          : Config.view == 1
                          ? ListView(
                        children: ls.map(getView).toList(),
                      )
                          : GridView.count(
                        crossAxisCount: 2,
                        children: ls.map(getView).toList(),
                      );
                    }),
              )
            ],
          ),
        )
      ),
    );
    //return ;
  }

  Widget getView(MenuItem item){
    switch(Config.view){
      case 1:
        return menu_item(item);
        break;
      case 2:
        return menu_item_cercile(item);
        break;
    };
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

  Widget menu_item_cercile(MenuItem item){
    return Padding(
      padding: const EdgeInsets.only(top:7.0,bottom: 7.0, left: 8.0,right: 8.0),
      child: InkWell(
        onTap: (){
          setState(() {
            _floatingCardState = FloatingPullUpState.uncollapsed;
            title = item.item_name;
            discrption = item.item_details;
            price = item.item_price;
            photo = item.item_photo;
          });
        },
        child: Column(
          children: [
            Container(
              width: 120,
              height: 120,
              decoration: BoxDecoration(
                borderRadius: BorderRadius.circular(100),
                image: DecorationImage(
                  image: NetworkImage(item.item_photo),
                  fit: BoxFit.cover,
                )
              ),
            ),
            SizedBox(height: 5,),
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
          ],
        ),
      ),
    );
  }

  DragHandleBuilder _customDragHandleBuilder =
      (context, constraints, beingDragged) {
    return CustomDrag(
      beingDragged: beingDragged,
    );
  };

  FloatingCardBuilder _customCardBuilder =
      (context, constraints, dragHandler, body, beingDragged) {
    return CustomCard(
      dragHandle: dragHandler,
      constraints: constraints,
      body: body,
      beingDragged: beingDragged,
    );
  };

}




