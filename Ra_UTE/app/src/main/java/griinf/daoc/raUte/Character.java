package griinf.daoc.raUte;

import android.content.Context;
import android.support.annotation.Nullable;

import com.threed.jpct.Loader;
import com.threed.jpct.Object3D;
import com.threed.jpct.SimpleVector;

import java.io.InputStream;
import java.util.Random;

public class Character {
    public static enum Type3D {
        _3DS,
        _OBJ
    }
    private Context context;
    private Object3D obj;
    Random rnd = new Random();
    private SimpleVector vector;

    private float probability = 0.1f;
    private float speed_factor = 0.2f;
    private float max_distance = 80f;
    private float min_distance = 20f;
    private float step_distance = 0.1f;

    public Character(Context context, Type3D type, int modelRes, float scale, @Nullable Integer materialRes, @Nullable String texture) {
        this.context = context;
        init(type, modelRes, scale, materialRes, texture);
    }

    public Object3D getObj() {
        return obj;
    }

    private void init(Type3D type, int modelRes, float scale, Integer materialRes, String texture) {
        vector = new SimpleVector();

        InputStream modelStream = context.getResources().openRawResource(modelRes);
        InputStream materialStream = null;
        if(type == Type3D._3DS) {
            obj = Object3D.mergeAll(Loader.load3DS(modelStream, scale));
        } else if (type == Type3D._OBJ) {
            if(materialRes != null) {
                materialStream = context.getResources().openRawResource(materialRes);
            }
            obj = Object3D.mergeAll(Loader.loadOBJ(modelStream, materialStream, scale));
        }
        if(texture != null) {
            obj.setTexture(texture);
        }
        obj.setCollisionMode(Object3D.COLLISION_CHECK_OTHERS);
        obj.build();
    }

    public void turn(float side, float up) {
        getObj().rotateY(side);
        getObj().rotateX(up);
    }

    public void move() {
        if(rnd.nextFloat() < probability) {
            float x = rnd.nextFloat() * speed_factor;
            float y = rnd.nextFloat() * speed_factor;
            float z = rnd.nextFloat() * speed_factor;

            if(rnd.nextBoolean()) x *= -1;
            if(rnd.nextBoolean()) y *= -1;
            if(rnd.nextBoolean()) z *= -1;

            vector.set(x, y, z);
        }
        getInRange(getObj(), vector, max_distance, min_distance, step_distance);
        getObj().translate(vector);
    }

    /*
    Si el actor no está en el rango definido min <-> max, se modifican obj y motion.
    - obj: se borra su matriz de translaciones y se lo acerca o aleja de acuerdo a step
    - motion: se encera
     */
    private void getInRange(Object3D obj, SimpleVector motion, float max, float min, float step) {
        boolean notInRange = false;
        SimpleVector goBackToZone = obj.getTransformedCenter();
        float distance = obj.getTransformedCenter().distance(SimpleVector.ORIGIN);
        System.out.println("Distance: " + distance + obj.getTransformedCenter());

        if(distance > max_distance) {
            notInRange = true;
            goBackToZone.scalarMul(1f - step);
            System.out.println(" ***Regresa: " + goBackToZone);
        } else if(distance < min_distance) {
            notInRange = true;
            goBackToZone.scalarMul(1f + step);
            System.out.println(" ***Empuja: " + goBackToZone);
        }

        if(notInRange) {
            obj.clearTranslation();
            obj.translate(goBackToZone);
            motion.set(0f, 0f, 0f);
        }
    }

}
