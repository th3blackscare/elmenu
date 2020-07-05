import 'dart:io' show Platform;
import 'package:elmenu/src/BLoC.dart';
import 'package:elmenu/src/FCM.dart';
import 'package:elmenu/src/pages/HomePage.dart';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter/widgets.dart';

void main() {
  BLoC bloc = BLoC();
  runApp(MyApp(bLoC: bloc));
}

class MyApp extends StatelessWidget {
  BLoC bLoC;

  MyApp({Key key, this.bLoC}); // This widget is the root of your application.
  @override
  Widget build(BuildContext context) {
    (Platform.isWindows)?null:PushNotificationsManager().init();
    SystemChrome.setEnabledSystemUIOverlays([]);
    return MaterialApp(
      theme: ThemeData(
        primarySwatch: Colors.red,
        primaryColor: Colors.red,
        visualDensity: VisualDensity.adaptivePlatformDensity,
      ),
      home: HomePageView(bloc: bLoC,),
    );
  }
}

