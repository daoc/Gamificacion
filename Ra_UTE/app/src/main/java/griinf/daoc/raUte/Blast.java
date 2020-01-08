package griinf.daoc.raUte;

import com.threed.jpct.Object3D;
import com.threed.jpct.Primitives;
import com.threed.jpct.RGBColor;
import com.threed.jpct.SimpleVector;
import com.threed.jpct.World;

import java.io.CharConversionException;

public class Blast {
    private Object3D blast;
    private World world;
    private SimpleVector motion;
    private final MainActivity master;
    private final Bullet bullet;
    private final Character monster;
    private boolean must_setup = true;

    private final float max_distance = 80f;
    private final float SPEED = 0.5f;
    private final float REACH = 0.5f;

    public Blast(MainActivity master, Bullet bullet, Character monster) {
        this.master = master;
        this.bullet = bullet;
        this.monster = monster;
        init();
    }

    private void init() {
//        //Explosión con un esfera
//        blast = Primitives.getSphere(1f);
//        blast.setOrigin(new SimpleVector(0, 0, 30));
//        blast.setTexture("blast");
//        blast.calcTextureWrapSpherical();
//        blast.setVisibility(false);
//        blast.build();

        //Explosión con un plano
        blast = Primitives.getPlane(40, 0.1f);
        blast.setOrigin(new SimpleVector(0, 0, 30));
        blast.setTexture("blast");
        blast.setVisibility(false);
        blast.build();
    }

    public Object3D getObj() {
        return blast;
    }

    public void move() {
        if(!master.monster_hit) return;
        if(must_setup) doSetup();
        blast.scale(1.1f);
        if(blast.getScale() > 20f) resetBlast();
    }

    private void doSetup() {
        blast.setVisibility(true);
        bullet.getObj().setVisibility(false);
        monster.getObj().setVisibility(false);
        must_setup = false;
    }

    private void resetBlast() {
        blast.setVisibility(false);
        bullet.getObj().setVisibility(true);
        monster.getObj().setVisibility(true);
        monster.getObj().clearTranslation();
        blast.setScale(1f);
        must_setup = true;
        master.monster_hit = false;
        master.bullet_fired = false;
    }

}
